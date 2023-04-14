<?php

namespace App\Http\Controllers;



use App\Models\BC130;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;
use Illuminate\Support\Facades\DB;

class APIController extends Controller
{


    public function itemsIndex()
    {
        $bc130 = new BC130();
        $items = $bc130->get_items_list();
        $items = $this->convert_from_latin1_to_utf8_recursively($items);
        //dd($items);
        return response()->json($items);
    }


    public function customersIndex()
    {
        $bc130 = new BC130();
        $customers = $bc130->get_customers_list();
        $customers = $this->convert_from_latin1_to_utf8_recursively($customers);
        //dd($customers);
        return response()->json($customers);
    }



    public function salesInvoiceHeadersList()
    {
        $bc130 = new BC130();
        $sales_invoice_headers = $bc130->get_sales_invoice_headers();
        $sales_invoice_headers = $this->convert_from_latin1_to_utf8_recursively($sales_invoice_headers);
        //dd($sales_invoice_headers);
        return response()->json($sales_invoice_headers);
    }



    public function salesInvoiceLinesList()
    {
        $bc130 = new BC130();
        $sales_invoice_lines = $bc130->get_sales_invoice_lines();
        $sales_invoice_lines = $this->convert_from_latin1_to_utf8_recursively($sales_invoice_lines);
        //dd($sales_invoice_lines);
        return response()->json($sales_invoice_lines);
    }



    public function valueEntries()
    {
        $bc130 = new BC130();
        $value_entries = $bc130->get_value_entries();
        $value_entries = $this->convert_from_latin1_to_utf8_recursively($value_entries);
        //dd($value_entries);
        return response()->json($value_entries);
    }


    public function convert_from_latin1_to_utf8_recursively($dat)
    {
        if (is_string($dat)) {
            return utf8_encode($dat);
        } elseif (is_array($dat)) {
            $ret = [];
            foreach ($dat as $i => $d) $ret[ $i ] = self::convert_from_latin1_to_utf8_recursively($d);

            return $ret;
        } elseif (is_object($dat)) {
            foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);

            return $dat;
        } else {
            return $dat;
        }
    }


}
