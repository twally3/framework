<?php


class Web {
  public function handle(Request $request, Closure $next) {
    echo "handled";
  }
}
