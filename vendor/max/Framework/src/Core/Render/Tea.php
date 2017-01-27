<?php 

namespace Framework\Core\Render;

use Framework\Core\Foundation\Application;

class Tea {

  /**
   * The application container
   * @var Framework\Core\Foundation\Application
   */
  protected $app;


  /**
   * The file extention
   * @var string
   */
  protected $extension = '.tea.php';


  /**
   * Bind dependencies
   * @param Application $app The application container
   */
  public function __construct(Application $app, $path='/App/Views/') {
    $this->app = $app;
    $this->basepath = $this->app->basepath . $path;
  }


  /**
   * Compile the view
   * @param  string $view The name of the view
   * @param  array  $data The Assoc list of data
   * @return void
   */
  public function make($view, $data = []) {
    $string = file_get_contents($this->basepath . $view . $this->extension);

    foreach ($data as $x => $y) {
      if (isset($$x)) {
        throw new \Exception("Variable naming conflict passed to view. '{$x}' is not an allowed variable name!");
      }
      global $$x;
      $$x = $y;
    }

    $string = $this->findExtend($string);
    $string = $this->findInclude($string);
    $string = $this->conditionals($string);
    $string = $this->loops($string);
    $string = $this->addVars($string);

    $tmpfname = tempnam($this->app->basepath . "/App/Views/tmp", "fakeEval");
    $handle = fopen($tmpfname, "w+");
    fwrite($handle, $string);
    fclose($handle);
    include $tmpfname;
    unlink($tmpfname);
  }


  /**
   * Find all the loops
   * @param  string $string The current state of the view
   * @return string         The state of the view with loops
   */
  protected function loops($string) {
    $string = $this->forLoop($string);
    $string = $this->for_each($string);

    $string = $this->breaks($string);

    return $string;
  }


  /**
   * Find the loop break points
   * @param  string $string The current state of the view
   * @return string         The view with loops
   */
  protected function breaks($string) {
    $find = '#\@break#';
    $replace = '<? break; ?>';

    $string = preg_replace($find, $replace, $string);
    return $string;
  }


  /**
   * Load foreach loops
   * @param  string $string The current state of the view
   * @return string         The view with foreach loops
   */
  protected function for_each($string) {
    $find = [
      '#\@foreach\s?\((.*?)\sas\s(.*?)\)\n#',
      '#\@endforeach#'
    ];
    $replace = [
      '<? foreach($1 as $2): ?>',
      '<? endforeach; ?>'
    ];
    $string = preg_replace($find, $replace, $string);
    return $string;
  }


  /**
   * Load for loops
   * @param  string $string The current state of the view
   * @return string         The view will for loops
   */
  protected function forLoop($string) {
    $find = [
      '#\@for\s?\((.*?)\)\n#',
      '#\@endfor\n#'
    ];

    $replace = [
      '<?for ($1):?>',
      '<?endfor;?>'
    ];

    $string = preg_replace($find, $replace, $string);
    return $string;
  }


  /**
   * Load conditional statements
   * @param  string $string The current state of the view
   * @return string         The view with conditionals
   */
  protected function conditionals($string) {
    $find = [
      '#\@if\s?((.*?)\n)#',
      '#\@elif\s?((.*?)\n)#',
      '#\@else\b#',
      '#\@endif#',
    ];
    $replace = [
      '<? if $1: ?>',
      '<? elseif $1: ?>',
      '<? else: ?>',
      '<? endif; ?>',
    ];

    $string = preg_replace($find, $replace, $string);
    return $string;
  }


  /**
   * Add variables to view
   * @param string $string The current state of the view
   * @param string  $data  The view with variables
   */
  protected function addVars($string, $data = []) {
    $find = [
      '#\{\$(.*?)\}#',
      '#\{\!\$(.*?)\}#',
      '#\{([^!].*?)\((.*?)\)\}#',
      '#\{\!(.*?)\((.*?)\)\}#'
    ];
    $replace = [
      '<?=htmlentities($$1);?>',
      '<?=$$1;?>',
      '<?=htmlentities($1($2));?>',
      '<?=$1($2);?>'
    ];

    $string = preg_replace($find, $replace, $string);
    return $string;
  }


  /**
   * Find @include
   * @param  string $string The current state of the view
   * @return string         The view with files included
   */
  protected function findInclude($string) {
    $find = '#\@include(?:\(\'(.+?)\'\))?#';

    preg_match_all($find, $string, $array);
    $i = 0;

    while (count($array) - 1 > 0 && $i < 1000) {
      $string = preg_replace_callback($find, [$this, 'include_file'], $string);
      preg_match_all($find, $string, $array);
      $array = (!empty($array[0])) ? $array : [];
      $i++;
    }

    return $string;
  }


  /**
   * Include the new view at @include
   * @param  array $matches The REGEX match
   * @return string         The view with the included file
   */
  protected function include_file($matches) {
    $file = implode('/', explode('.', $matches[1]));
    $string = file_get_contents($this->basepath . $file . $this->extension);
    return $string;
  }


  /**
   * Find all @extends
   * @param  string $string The current state of the view
   * @return string         The view with extension
   */
  protected function findExtend($string) {
    // $findSection = "#\@section\(\'(.*?)\'\)([^.]*?)\@endsection#";
    $findSection = "/\@section\(\'(.*?)\'\)(.*?)\@endsection/s";
    $findExtend = '#\@extends\(\'(.*?)\'\)#';
    $findOutput = '#\@output\(\'(.*?)\'\)#';
    $sections = [];

    // dd($string);
    preg_match_all($findSection, $string, $array);

    for ($i = 0; $i < count($array[1]); $i++) {
      if (!empty($array[0][$i])) {
        $sections[$array[1][$i]] = $array[2][$i];
      }
    }
    $this->sections = $sections;

    preg_match($findExtend, $string, $extends);
    $new_string = $this->extend($extends, $string);

    $new_string = preg_replace_callback($findOutput, [$this, 'output'], $new_string);

    preg_match_all($findExtend, $new_string, $array);
    $array = (empty($array[0])) ? [] : $array;

    if (count($array) - 1 > 0) {
      $new_string = $this->findExtend($new_string);
    }

    return $new_string;
  }


  /**
   * Finds @output in files
   * @param  array $matches The Regex Match
   * @return mixed          The found section or empty string
   */
  protected function output($matches) {
    return (isset($this->sections[$matches[1]])) ? $this->sections[$matches[1]] : '';
  }


  /**
   * Include the extend file
   * @param  array  $extends The extend matches
   * @param  string $string  The current state of the view
   * @return string          The view with extension
   */
  protected function extend($extends, $string) {
    if (!empty($extends[0])) {
      $file = implode('/', explode('.', $extends[1]));
      $new_string = file_get_contents($this->basepath . $file . $this->extension);
      return $new_string;
    }
    return $string;
  }
}
