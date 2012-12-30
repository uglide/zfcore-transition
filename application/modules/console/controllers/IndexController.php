<?php
/**
 * IndexController.php
 * Created by Igor Malinovskiy <u.glide@gmail.com>.
 * Date: 16.07.12
 */
class Console_IndexController extends Core_Controller_Cli
{
    public function init()
    {
        $this->msg("Welcome to Console Module :)");
    }

    /**
     * Just test action
     */
    public function indexAction()
    {
        $this->msg("index");
    }
}
