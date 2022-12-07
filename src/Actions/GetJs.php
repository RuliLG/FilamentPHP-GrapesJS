<?php

namespace Calima\FilamentPHPGrapesJS\Actions;

class GetJs
{
    public function __invoke()
    {
        return view('js.grapesjs-editor');
    }
}
