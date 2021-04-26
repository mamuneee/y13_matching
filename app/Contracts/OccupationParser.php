<?php

namespace App\Contracts;

interface OccupationParser
{
    function setScope($scope);
    function getScope();
    function getUrl($occupation_code);
    function list();
    function get($occupation_code);
}