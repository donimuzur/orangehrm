<div class="box">
    <div class="head">
        <h1><?php echo __('Upload CV Candidate'); ?></h1>
    </div>
    <div class="inner">
        <?php include_partial('global/flash_messages', array('prefix' => 'uploadCV')); ?>
        <form action="<?php echo url_for("customRecruitment/updateUploadCV"); ?>" id="uploadCVForm" name="uploadCVForm" method="post"  enctype="multipart/form-data">
            <?php echo $form['_csrf_token']; ?>
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
                        <input type="hidden" name="MAX_FILE_SIZE" value="5242880" />                        
                        <?php echo $form['ufile']->render(); ?>
                        <!-- <input type="file" name="viewUploadCV[ufile]" id="viewUploadCV_ufile" multiple/> -->
                        <?php echo "<label class=\"fieldHelpBottom\">" . __("Accepts .docx, .doc, .odt, .pdf, .rtf, .txt up to 5MB") . "</label>"; ?>
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
            "viewUploadCV[ufile]" : {required:true, attachment:true},
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
            "viewUploadCV[ufile]": {
                required: lang_NameRequired,
                attachment :lang_PleaseSelectAFile,
            } 
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
