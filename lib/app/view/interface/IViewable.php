<?php



namespace lib\app\view\interface;


interface IViewable
{
  /**
   * Returns the base assets directory
   *
   * @return string
   */
  public function getAssetDirectory();

  /**
   * Returns the base views directory
   *
   * @return string
   */
  public function getViewsDirectory();
}
