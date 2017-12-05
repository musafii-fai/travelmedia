<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

function openBox($alert = "",$title="")
{
	return '
			<div class="box box-'.$alert.'">
				<div class="box-header">
					<h3 class="box-title">'.$title.'</h3>
				</div>
				<div class="box-body">
		';
}

function closeBox()
{
	return "
				</div>
			</div>
		";
}

function alertDanger($content = "Danger")
{
	return "<div class='alert alert-danger'><i class='fa fa-ban'></i> <b>".$content."</b></div>";
}

function alertInfo($content = "Info")
{
	return "<div class='alert alert-info'><i class='fa fa-info'></i> <b>".$content."</b></div>";
}

function alertSuccess($content = "Success")
{
	return "<div class='alert alert-success'><i class='fa fa-check'></i> <b>".$content."</b></div>";
}

function alertWarning($content = "Warning")
{
	return "<div class='alert alert-warning'><i class='fa fa-warning'></i> <b>".$content."</b></div>";
}