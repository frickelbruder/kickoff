<?php
namespace Frickelbruder\KickOff\Yaml;

class Yaml {

    public function fromFile($fileName) {
        return \Symfony\Component\Yaml\Yaml::parse(file_get_contents($fileName));
    }

}