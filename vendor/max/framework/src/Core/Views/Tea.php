<?php namespace Framework\Core\Views;

class Tea {

  protected $extension = '.tea.php';

  public function view($view, $data = []) {
    $string = file_get_contents('../app/views/' . $view . $this->extension);

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
    $string = $this->addNamespace($string);

    // echo '<pre>';
    // echo htmlentities($string);
    // die;

    $tmpfname = tempnam("..app/views/tmp", "fakeEval");
    $handle = fopen($tmpfname, "w+");
    fwrite($handle, $string);
    fclose($handle);
    include $tmpfname;
    unlink($tmpfname);
  }

  protected function addNamespace($string) {
    return "<? use Framework\Core\HTTP\Route as Route; ?>$string";
  }

  protected function loops($string) {
    $string = $this->forLoop($string);
    $string = $this->forEach($string);

    $string = $this->breaks($string);

    return $string;
  }

  protected function breaks($string) {
    $find = '#\@break#';
    $replace = '<? break; ?>';

    $string = preg_replace($find, $replace, $string);
    return $string;
  }

  protected function forEach($string) {
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

  protected function findInclude($string) {
    $find = '#\@include(?:\(\'(.+?)\'\))?#';

    preg_match_all($find, $string, $array);
    $i = 0;

    while (count($array) - 1 > 0 && $i < 1000) {
      $string = preg_replace_callback($find, [$this, 'include'], $string);
      preg_match_all($find, $string, $array);
      $array = (!empty($array[0])) ? $array : [];
      $i++;
    }

    return $string;
  }

  protected function include($matches) {
    $file = implode('/', explode('.', $matches[1]));
    $string = file_get_contents('../app/views/' . $file . $this->extension);
    return $string;
  }

  protected function findExtend($string) {
    $findSection = "#\@section\(\'(.*?)\'\)([^.]*?)\@endsection#";
    $findExtend = '#\@extends\(\'(.*?)\'\)#';
    $findOutput = '#\@output\(\'(.*?)\'\)#';
    $sections = [];

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

  protected function output($matches) {
    return (isset($this->sections[$matches[1]])) ? $this->sections[$matches[1]] : '';
  }

  protected function extend($extends, $string) {
    if (!empty($extends[0])) {
      $file = implode('/', explode('.', $extends[1]));
      $new_string = file_get_contents('../app/views/' . $file . $this->extension);
      return $new_string;
    }
    return $string;
  }
}
