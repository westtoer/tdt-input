<?php

namespace tdt\input\emlp\helper\json;

interface Listener {

  public function start_document();
  public function end_document();

  public function start_object();
  public function end_object();

  public function start_array();
  public function end_array();

  // Key will always be a string
  public function key($key);

  // Note that value may be a string, integer, boolean, array, etc.
  public function value($value);

}
