<?php namespace Base;


interface Middleware
{
    public function handle($request);
}