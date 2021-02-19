<div class="box">
    <div class="head">
        <h1><?php echo __('Upload CV Candidate'); ?></h1>
    </div>
    <div class="inner">
        <?php include_partial('global/flash_messages', array('prefix' => 'uploadCV')); ?>
        <form action="<?php echo url_for("customRecruitment/viewUploadCV"); ?>" id="uploadCVForm" name="uploadCVForm" method="post"  enctype="multipart/form-data">
            <fieldset>
                <ol class="normal">
					<li>
                        <?php echo $form['vacancyPosition']->renderLabel(__('Vacancy Position').'<em> *</em>'); ?>
                        <?php echo $form['vacancyPosition']->render(array("class" => "block default editable", "maxlength" => 50)); ?>
                    </li> 
                    <li>
                        <?php echo $form['uploadDate']->renderLabel(); ?>
                        <?php echo $form['uploadDate']->render(); ?>
                    </li>
                    <li class="fieldHelpContainer">
                        <label id="selectFileSpan" style="height:100%"><?php echo __("Select File")?> <em>*</em></label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />                        
                        <?php echo $form['ufile']->render(); ?>
                        <!-- <input type="file" name="ufile" id="ufile" multiple/> -->
                        <?php echo "<label class=\"fieldHelpBottom\">" . __(CommonMessages::FILE_LABEL_DOC) . "</label>"; ?>
                    </li>
					<li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>
                <p>
                    <input type="button" name="btnSaveAttachment" id="btnSaveAttachment" value="<?php echo __("Upload");?>" />
                    <input type="button" class="cancel" id="cancelButton" value="<?php echo __("Cancel"); ?>" />
                </p>
            </fieldset>
        </form>
    </div>
</div>
<div id="attachmentList" class="box miniList">
    <div class="head">
        <h1><?php echo __('Attachments'); ?></h1>
    </div>
    <div class="inner">
        
        <?php include_partial('global/flash_messages', array('prefix' => 'listAttachmentPane')); ?>

        <form name="frmEmpDelAttachments" id="frmEmpDelAttachments" method="post" action="<?php echo url_for('pim/deleteAttachments?empNumber='.$employee->empNumber); ?>">

            <?php echo $deleteForm['_csrf_token']; ?>
            <input type="hidden" name="EmpID" value="<?php echo $employee->empNumber;?>"/>

            <p id="attachmentActions">
                <?php if ($permission->canCreate()) : ?>
                <input type="button" class="addbutton" id="btnAddAttachment" value="<?php echo __("Add");?>" />
                <?php elseif (!$hasAttachments) :
                        echo __(TopLevelMessages::NO_RECORDS_FOUND);
                      endif; // $permission->canCreate() ?>
                <?php if ($permission->canDelete() && $hasAttachments) : ?>
                 <input type="button" class="delete" id="btnDeleteAttachment" value="<?php echo __("Delete");?>"/>
                <?php endif; // $permission->canDelete() && $hasAttachments ?>
            </p>
            
            <?php if ($hasAttachments) : ?>
            
                <table id="tblAttachments" cellpadding="0" cellspacing="0" width="100%" class="table tablesorter">
                    <thead>
                        <tr>
                            <?php if ($permission->canDelete()){?>
                            <th width="2%"><input type="checkbox" id="attachmentsCheckAll" class="checkboxAtch"/></th>
                            <?php }?>
                            <th width="15%"><?php echo __("Vacancy Position")?></th>
                            <th width="15%"><?php echo __("Upload Date")?></th>
                            <th width="15%"><?php echo __("File Name")?></th>
                            <th width="38%"><?php echo __("Description")?></th>
                            <th width="10%"><?php echo __("Size")?></th>
                            <th width="10%"><?php echo __("Type")?></th>
                            <th width="10%"><?php echo __("Date Added")?></th>
                            <th width="10%"><?php echo __("Added By")?></th>
                            <th width="5%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php
                            $disabled = ($permission->canDelete()) ? "" : 'disabled="disabled"';
                            $row = 0;
                        ?>
                        
                        <?php foreach ($attachmentList as $attachment) : ?>
                        <?php $cssClass = ($row%2) ? 'even' : 'odd'; ?>
                            
                            <tr class="<?php echo $cssClass;?>">
                                <?php if ($permission->canDelete()){?>
                                <td class="center">
                                    <input type="checkbox" <?php echo $disabled;?> class="checkboxAtch" 
                                    name="chkattdel[]" value="<?php echo $attachment->attach_id; ?>"/>
                                </td>
                                <?php }?>
                                <td>
                                    <?php if (!$permission->canDelete()){?>
                                        <input type="hidden" <?php echo $disabled;?> 
                                               name="chkattid[]" value="<?php echo $attachment->attach_id; ?>"/>                                    
                                    <?php }?>
                                    <a title="<?php echo __('Click to download'); ?>" target="_blank" class="fileLink tiptip"
                                    href="<?php echo url_for('pim/viewAttachment?empNumber='.$employee->empNumber . '&attachId=' . $attachment->attach_id);?>">
                                    <?php echo $attachment->filename; ?></a>
                                </td>
                                <td>
                                    <?php echo $attachment->description; ?>
                                </td>
                                <td>
                                    <?php echo add_si_unit($attachment->size); ?>
                                </td>
                                <td>
                                    <?php echo $attachment->file_type; ?>
                                </td>
                                <td>
                                    <?php echo set_datepicker_date_format($attachment->attached_time); ?>
                                </td>
                                <?php
                                $performedBy = $attachment->attached_by_name;
                                $performedBy = ($performedBy == 'Admin')?__($performedBy):$performedBy;
                                ?>
                                <td>
                                    <?php echo $performedBy; ?>
                                </td>
                                <?php if ($permission->canUpdate()) : ?>                                
                                <td>
                                    <a href="#" class="editLink"><?php echo __("Edit"); ?></a>
                                </td>
                                <?php else: ?>
                                <td>
                                </td>
                                <?php endif; ?>
                            </tr>
                        
                        <?php $row++; ?>
                        <?php endforeach; ?>
                        
                    </tbody>
                </table>
            
            <?php endif; // $hasAttachments ?>
        </form> 
    </div>
</div> 

<script type="text/javascript">
    //<![CDATA[
    //we write javascript related stuff here, but if the logic gets lengthy should use a seperate js file
    var edit = "<?php echo __js("Edit"); ?>";
    var save = "<?php echo __js("Save"); ?>";
    var upload = "<?php echo __js("Upload"); ?>";
    var lang_Required = "This Field is mandatory";
    var lang_lastNameRequired = "<?php echo __js(ValidationMessages::REQUIRED); ?>";
    var lang_selectGender = "<?php echo __js(ValidationMessages::REQUIRED); ?>";
    var lang_processing = '<?php echo __js(CommonMessages::LABEL_PROCESSING."...");?>';
    var lang_invalidDate = '<?php echo __js(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var hideAttachmentListOnAdd = <?php echo $hasAttachments ? 'false' : 'true';?>;
    var lang_EditAttachmentHeading = "<?php echo __js("Edit Attachment"); ?>";
    var lang_AddAttachmentHeading = "<?php echo __js("Add Attachment"); ?>";
    var lang_SelectFile = "<?php echo __js("Select File");?>";
    var lang_ReplaceWith = "<?php echo __js("Replace With");?>";
    var lang_PleaseSelectAFile = "<?php echo __js(ValidationMessages::REQUIRED);?>";
    var lang_CommentsMaxLength = "<?php echo __js(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 200));?>";
    var lang_SelectAtLeastOneAttachment = "<?php echo __js(TopLevelMessages::SELECT_RECORDS); ?>";
    var hasError = <?php echo ($sf_user->hasFlash('saveAttachmentPane.warning'))?'true':'false'; ?>;
    var lang_NameRequired = '<?php echo __js(ValidationMessages::REQUIRED); ?>';
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var displayDateFormat = '<?php echo str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())); ?>';
    var errorForInvalidFormat='<?php echo __js(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var clearAttachmentMessages = true;
    
    $(document).ready(function() {
        
        $('#btnDeleteAttachment').attr('disabled', 'disabled');

        if (!hasError) {
            $('#addPaneAttachments').hide();
        }
        
        $('#currentFileLi').hide();
        $('#btnCommentOnly').hide();
        
        $("#uploadCVForm").data('add_mode', true);

        jQuery.validator.addMethod("attachment",
        function() {

            var addMode = $("#uploadCVForm").data('add_mode');
            if (!addMode) {
                return true;
            } else {
                var file = $('#ufile').val();
                return file != "";
            }
        }, ""
    );
    
    $("#uploadCVForm").validate({
        rules: {
            "ufile" : {attachment:true},
            "viewUploadCV[vacancyPosition]" : {required:true},
            "viewUploadCV[uploadDate]" :{
                required: true, 
                valid_date: function() {
                    return {
                        format:datepickerDateFormat,
                        required:true,
                        displayFormat:displayDateFormat
                    }
            }},
        },
        messages: {
            "viewUploadCV[vacancyPosition]" :{
                required: lang_NameRequired
            }, 
            "viewUploadCV[uploadDate]" : {
                required: lang_NameRequired,
                valid_date: errorForInvalidFormat
            },
            "ufile": lang_PleaseSelectAFile,
        }
    });

        //if check all button clicked
        $("#attachmentsCheckAll").click(function() {
            $("table#tblAttachments tbody input.checkboxAtch").prop('checked', false);
            if($("#attachmentsCheckAll").prop('checked')) {
                $("table#tblAttachments tbody input.checkboxAtch").prop('checked', true);
            }
            
            if($('table#tblAttachments tbody .checkboxAtch:checkbox:checked').length > 0) {
                $('#btnDeleteAttachment').removeAttr('disabled');
            } else {
                $('#btnDeleteAttachment').attr('disabled', 'disabled');
            }
        });

        //remove tick from the all button if any checkbox unchecked
        $("table#tblAttachments tbody input.checkboxAtch").click(function() {
            $("#attachmentsCheckAll").prop('checked', false);
            if($("table#tblAttachments tbody input.checkboxAtch").length == $("table#tblAttachments tbody input.checkboxAtch:checked").length) {
                $("#attachmentsCheckAll").prop('checked', true);
            }
            
            if($('table#tblAttachments tbody .checkboxAtch:checkbox:checked').length > 0) {
                $('#btnDeleteAttachment').removeAttr('disabled');
            } else {
                $('#btnDeleteAttachment').attr('disabled', 'disabled');
            }
        });
        // Edit an attachment in the list
        $('#attachmentList a.editLink').click(function(event) {
            event.preventDefault();
            
            if (clearAttachmentMessages) {
                $("#attachmentsMessagebar").text("").attr('class', "");
            }
            
            attachmentValidator.resetForm();
            
            var row = $(this).closest("tr");            
            var fileName = row.find('a.fileLink').text();
            var seqNo;
            var description;
            
            var checkBox = row.find('input.checkboxAtch:first');
            if (checkBox.length > 0) {
                seqNo = checkBox.val();
                description = row.find("td:nth-child(3)").text();
            } else {
                seqNo = row.find('input[type=hidden]:first').val();
                description = row.find("td:nth-child(2)").text();                
            }
            description = jQuery.trim(description); 

            $('#seqNO').val(seqNo);
            $('#ufile').removeAttr("disabled");
            
            $('#txtAttDesc').val(description);

            $("#uploadCVForm").data('add_mode', false);

            $('#btnCommentOnly').show();

            // hide validation error messages
            $("label.error1col[generated='true']").css('display', 'none');
            $('#attachmentActions').hide();
            
            $("table#tblAttachments input.checkboxAtch").hide();
            
            $('#addPaneAttachments').show();
            $('#saveHeading h1').text(lang_EditAttachmentHeading);
            
            $('#currentFileLi').show();
            $('#currentFileSpan').text(fileName);
            $('#selectFileSpan').text(lang_ReplaceWith);
            
        });

        // Add a emergency contact
        $('#btnAddAttachment').click(function() {
            
            $('#currentFileLi').hide();
            $('#selectFileSpan').text(lang_SelectFile);
            
            if (clearAttachmentMessages) {
                $("#attachmentsMessagebar").text("").attr('class', "");
            }
            $('#seqNO').val('');
            $('#attachmentEditNote').text('');
            $('#txtAttDesc').val('');

            $("#uploadCVForm").data('add_mode', true);
            $('#btnCommentOnly').hide();

            // hide validation error messages
            $("label.error1col[generated='true']").css('display', 'none');
            
            $('#ufile').removeAttr("disabled");
            $('#attachmentActions').hide();
            $('#saveHeading h1').text(lang_AddAttachmentHeading);
            $('#addPaneAttachments').show();
            
            $("table#tblAttachments input.checkboxAtch").hide();
            $("table#tblAttachments a.editLink").hide();
            
            if (hideAttachmentListOnAdd) {
                $('#attachmentList').hide();
            }
        });
        
        $('#cancelButton').click(function() {
            $("#attachmentsMessagebar").text("").attr('class', "");
            
            attachmentValidator.resetForm();
            $('#ufile').val('');
            $('#vacancyPosition').val('');
            $('#uploadDate').val('');
            // $('#attachmentList').show();
            // $("table#tblAttachments input.checkboxAtch").show();
            // $("table#tblAttachments a.editLink").show();            
        });
        
        $('#btnDeleteAttachment').click(function() {

            var checked = $('#attachmentList input:checked').length;

            if (checked > 0) {
                $('#frmEmpDelAttachments').submit();
            }
            
        });

        $('#btnSaveAttachment').click(function() {
            $("#uploadCVForm").data('add_mode', true);
            $('#uploadCVForm').submit();
        });
        
        
        <?php if ($attEditPane) { ?>
                clearAttachmentMessages = false;
        <?php    if ($attSeqNO === false) { ?>
            
                $('#btnAddAttachment').trigger('click');
                
        <?php } else { ?>
            
                $('table#tblAttachments input.checkboxAtch[value="<?php echo $attSeqNO;?>"]').
                    closest('tr').find('a.editLink').trigger('click');
                
        <?php } ?>
            
            $('#txtAttDesc').val('<?php echo $attComments;?>');
                clearAttachmentMessages = true;       
        <?php } ?>
            
        
            //
            // Scroll to bottom if neccessary. Works around issue in IE8 where
            // using the <a name="attachments" is not sufficient
            //
        <?php  if ($scrollToAttachments) { ?>
                window.scrollTo(0, $(document).height());
        <?php } ?>
            });
        //]]>
  
</script>
