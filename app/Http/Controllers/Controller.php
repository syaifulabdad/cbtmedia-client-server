<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public $model;
    public $primaryKey;
    public $title;
    public $cUrl;

    public $dataTable;
    public $dataTableOrder;
    public $dataTableFilter;
    public $formData;
    public $tingkat = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => "X", 11 => "XI", 12 => "XII"];
    public $tingkatSD = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];
    public $tingkatSMP = [7 => 'VII', 8 => 'VIII', 9 => 'IX'];
    public $tingkatSMA = [10 => "X", 11 => "XI", 12 => "XII"];

    public function __construct()
    {
    }
}
