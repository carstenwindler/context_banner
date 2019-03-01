<?php
$EM_CONF[$_EXTKEY] = array (
  'title' => 'Context Banner',
  'description' => 'Adds a small Banner in both FE and BE and changes title tag to easier distinct development, testing and production context',
  'category' => 'misc',
  'shy' => 1,
  'version' => '1.0.0',
  'dependencies' => '',
  'conflicts' => '',
  'priority' => '',
  'loadOrder' => '',
  'module' => '',
  'state' => 'stable',
  'uploadfolder' => 1,
  'createDirs' => '',
  'modify_tables' => '',
  'clearcacheonload' => 1,
  'lockType' => '',
  'author' => 'Carsten Windler',
  'author_email' => 'carsten@carstenwindler.de',
  'author_company' => '',
  'CGLcompliance' => '',
  'CGLcompliance_note' => '',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '7.6.0-8.7.99',
      'php' => '7.0.0-7.1.99',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  'suggests' => 
  array (
  ),
  'autoload' => 
  array (
    'psr-4' => 
    array (
      'CarstenWindler\\ContextBanner\\' => 'Classes',
    ),
  ),
  'autoload-dev' => 
  array (
    'psr-4' => 
    array (
      'CarstenWindler\\ContextBanner\\Tests\\' => 'Tests',
    ),
  ),
);
