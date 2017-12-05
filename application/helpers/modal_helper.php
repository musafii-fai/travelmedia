<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

function modalButtonTarget($idTarget,$nameButton,$color="primary",$icon="fa-plus",$size="")
{
  return '
        <button class="btn btn-'.$color.' btn-'.$size.'" data-toggle="modal" data-target="#'.$idTarget.'"><span class="fa '.$icon.'"></span>'." ".$nameButton.'</button><br><br>
      ';
}

function modalAnchorTarget($idTarget,$url,$nameButton,$color="default",$icon="fa-plus",$size="")
{
  return '
          <a href="'.$url.'" data-toggle="modal" data-target="#'.$idTarget.'" class="btn btn-'.$color.'"><span class="fa '.$icon.'"></span>'." ".$nameButton.'</a>
        ';
}

function modalSaveOpen($idTarget = "modalForm",$size = "",$title="Empty Title")
{
  return '
            <div class="modal fade" id="'.$idTarget.'">
            <div class="modal-dialog modal-'.$size.'">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" id="modalButtonClose" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times-circle"></i></button>
                  <h4 class="modal-title">'.$title.'</h4>
                </div>
                <div class="modal-body">
        ';  
}

function modalSaveClose($nameButton="Save",$id = "modalButtonSave",$btnColor = "primary")
{
  return '
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-'.$btnColor.'" id="'.$id.'">'.$nameButton.'</button>
                  <button type="button" class="btn btn-default" id="modalButtonClose" data-dismiss="modal">Close</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->
        ';
}

function modalDeleteShow($title = "Delete")
{
  return '
             <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" id="modalButtonCloseDelete" data-dismiss="modal" aria-label="Close"><i class="fa fa-times-circle"></i></button>
                    <h4 class="modal-title" id="myModalLabel">'.$title.'</h4>
                  </div>
                  <div class="modal-body">
                    <div id="contentDelete"><p><b>Apakah anda yakin ingin menghapus data ini.?</b></p></div>
                    <div id="inputMessageDelete"></div>
                 </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="modalButtonDelete">OK Delete</button>
                    <button type="button" class="btn btn-default" id="modalButtonCloseDelete" data-dismiss="modal">Cancel</button>
                  </div>
                </div>
              </div>
            </div>
        ';  
}