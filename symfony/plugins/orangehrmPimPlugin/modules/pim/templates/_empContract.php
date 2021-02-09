<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 *
 */
?>

<?php
$numEmpContracts = count($employeeContracts);
$haveContracts = $numEmpContracts > 0;
?>
<?php if ($form->hasErrors()): ?>
<span class="error">
<?php
echo $form->renderGlobalErrors();

foreach($form->getWidgetSchema()->getPositions() as $widgetName) {
  echo $form[$widgetName]->renderError();
}
?>
</span>
<?php endif; ?>

<div id="employeeContractNew">
    <?php if ($empContractNewPermissions->canUpdate()) { ?>
    <div id="addPaneEmpContractNew">
        <div class="head">
            <h1 id="employeeContractNewHeading"><?php echo __('Add Employee Contact'); ?></h1>
        </div>
        <div class="inner">
            <form name="frmEmpContractNew" id="frmEmpContractNew" method="post" action="<?php echo url_for('pim/updateEmpContractnew?empNumber=' . $empNumber); ?>">
                <?php echo $form['_csrf_token']; ?>
                <?php echo $form["emp_number"]->render(); ?>
                <?php echo $form["emp_contract_number"]->render(); ?>
                <fieldset>
                    <ol>
                        <li>
                            <?php echo $form['emp_contract_start_date']->renderLabel(__('Start Date'). ' <em>*</em>'); ?>
                            <?php echo $form['emp_contract_start_date']->render(array("class" => "formDateInput")); ?>    
                        </li>
                        <li>
                            <?php echo $form['emp_contract_end_date']->renderLabel(__('End Date'). ' <em>*</em>'); ?>
                            <?php echo $form['emp_contract_end_date']->render(array("class" => "formDateInput")); ?>    
                        </li>
                        <li>
                            <?php echo $form['keterangan']->renderLabel(__('Note')); ?>
                            <?php echo $form['keterangan']->render(); ?>    
                        </li>
                        <li class="required">
                            <em>*</em><?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                        </li>
                    </ol>
                    <p>
                        <input type="button" class="" name="btnSaveEmpContractNew" id="btnSaveEmpContractNew" value="<?php echo __("Save"); ?>"/>
                        <input type="button" id="btnCancelEmpContractNew" class="reset" value="<?php echo __("Cancel"); ?>"/>
                    </p>
                </fieldset>
            </form>
        </div>
    </div> <!-- addPaneEmgContact -->
    <?php } ?>

    <div class="miniList" id="listEmployeeContract">
        <div class="head">
            <h1><?php echo __("Employee Contracts List"); ?></h1>
        </div>
        
        <div class="inner">
            <?php if ($empContractNewPermissions->canRead()) : ?>
            <?php include_partial('global/flash_messages', array('prefix' => 'empContractNewMessage')); ?>
            <form name="frmEmpDelContractNew" id="frmEmpDelContractNew" method="post" action="<?php echo url_for('pim/deleteEmpContractNew?empNumber=' . $empNumber); ?>">
                <?php echo $deleteForm['_csrf_token']->render(); ?>
                <?php echo $deleteForm['emp_number']->render(); ?>
                <p id="empContractNewListActions">
                    <?php if ($empContractNewPermissions->canUpdate()) { ?>
                    <input type="button" class="" id="btnAddEmpContractNew" value="<?php echo __("Add"); ?>"/>
                    <?php } ?>
                    <?php if ($empContractNewPermissions->canDelete()) { ?>
                    <input type="button" class="delete" id="delEmpContractNewBtn" value="<?php echo __("Delete"); ?>"/>
                    <?php } ?>
                </p>
                <table id="empContractNew_list" class="table hover">
                    <thead>
                        <tr><?php if ($empContractNewPermissions->canDelete()) { ?>
                            <th class="check" id='chkEmpContractNew' style="width:2%"><input type='checkbox' id='checkAllEmpContractNew' class="checkbox" /></th>
                            <?php } ?>
                            <th class="empContractNumber"> <?php echo __("Contract No"); ?></th>
                            <th><?php echo __("Start Date"); ?></th>
                            <th><?php echo __("End Date"); ?></th>
                            <th><?php echo __("Status"); ?></th>
                            <th style="min-width: 200px;"><?php echo __("Action"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!$haveContracts) { ?>
                        <tr>
                            <?php if ($empContractNewPermissions->canDelete()) { ?>
                            <td class="check" id='chkEmpContractNew'></td>
                            <?php } ?>
                            <td><?php echo __(TopLevelMessages::NO_RECORDS_FOUND); ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php } else { ?>                        
                        <?php
                        $row = 0;
                        foreach ($employeeContracts as $employeeContracts) :
                            $cssClass = ($row % 2) ? 'even' : 'odd';
                            echo '<tr class="' . $cssClass . '">';
                            if ($empContractNewPermissions->canDelete()) {
                            echo "<td class='check' id='chkEmpContractNew'><input type='checkbox' class='checkbox' id='checkEmpContractNew' name='chkEmpContractNewdel[]' value='" . $employeeContracts->emp_contract_number . "'/></td>";
                            } else {
                            ?>
                            <input type='hidden' class='checkbox' value="<?php echo $employeeContracts->emp_contract_number; ?>"/>
                            <?php
                            }
                            ?>
                            <td class="empContactNewName">
                                <?php if ($empContractNewPermissions->canUpdate()) { ?>
                                <a href="#addPaneEmpContractNew"><?php echo 'Contract No - '.$employeeContracts->emp_contract_number; ?></a>
                                <?php
                                } else {
                                echo 'Contract No - '.$employeeContracts->emp_contract_number;
                                }
                                ?>
                            </td>
                            <input type="hidden" id="emp_contract_number<?php echo $employeeContracts->emp_contract_number; ?>" value="<?php echo $employeeContracts->emp_contract_number; ?>" />
                            <input type="hidden" id="emp_contract_start_date_<?php echo $employeeContracts->emp_contract_number; ?>" value="<?php echo set_datepicker_date_format($employeeContracts->emp_contract_start_date); ?>" />
                            <input type="hidden" id="emp_contract_end_date_<?php echo $employeeContracts->emp_contract_number; ?>" value="<?php echo set_datepicker_date_format($employeeContracts->emp_contract_end_date); ?>" />
                            <input type="hidden" id="status_<?php echo $employeeContracts->status; ?>" value="<?php echo $employeeContracts->status; ?>" />
                            <input type="hidden" id="keterangan_<?php echo $employeeContracts->emp_contract_number; ?>" value="<?php echo $employeeContracts->keterangan; ?>" />
                            
                            
                            <?php
                            echo '<td>' . set_datepicker_date_format($employeeContracts->emp_contract_start_date) . '</td>';
                            echo '<td>' . set_datepicker_date_format($employeeContracts->emp_contract_end_date) . '</td>';
                            if($employeeContracts->status)
                            {
                                echo '<td>Contract Active</td>';
                            }
                            else{
                                echo '<td>Contract Ended</td>';
                            }
                            
                            $startTd = '<td>';
                            $content1 ='';
                            $content2='';
                            if ($empContractNewPermissions->canUpdate()) {
                                if($employeeContracts->status)
                                {
                                    $content1 = '<input type="button" class="" id="extend_'.$employeeContracts->emp_contract_number.'" value="'.__("Extend").'" onclick="extend('.$employeeContracts->emp_contract_number.')"/>';
                                    $content2 = '<input type="button" class="delete" id="endContract_'.$employeeContracts->emp_contract_number.'" value="'.__("End Contract").'" data-toggle="modal" href="#endContractModal" onclick="endContract('.$employeeContracts->emp_contract_number.')" />';
                                }
                            }
                            $endTd = '</td>';
                            echo $startTd. $content1.' '.$content2.$endTd;
                            echo '</tr>';
                            $row++;
                        endforeach;
                        ?>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
            <?php else : ?>
            <div><?php echo __(CommonMessages::RESTRICTED_SECTION); ?></div>
            <?php endif; ?>
        </div>
    </div> <!-- miniList -->
</div> <!-- Box -->

<?php if ($empContractNewPermissions->canRead()) { ?>
<div class="modal hide" id="endContractModal">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo __('End Employee Contract') ?></h3>
    </div>
    <div class="modal-body">
        <form name="frmEmpEndContractNew" id="frmEmpEndContractNew" method="post" action="<?php echo url_for('pim/UpdateEmpEndContractNew?empNumber=' . $empNumber); ?>">
            <input type="hidden" name="emp_contract_number" class="EndContract" id="emp_contract_number" value="" />
            <p><?php echo __("are you sure ?")?></p>
        </form>
    </div>
    <div class="modal-footer">
        <?php if ($empContractNewPermissions->canUpdate()) { ?>
        <input type="button" id="dialogConfirmEndContract" class="btn" value="<?php echo __('Confirm'); ?>" />
        <?php } ?>
        <input type="button"  id="dialogCancelEndContract" name="dialogCancel" class="btn reset" data-dismiss="modal" value="<?php echo __('Cancel'); ?>" />
    </div>
</div>
<?php }?>
<script type="text/javascript">
    //<![CDATA[
    // Move to separate js after completing initial work
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var lang_validDateMsg = '<?php echo __js(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))); ?>'
    var lang_startDateAfterEndDate = "<?php echo __js('End date should be after start date'); ?>";

    $('#delEmpContractNewBtn').attr('disabled', 'disabled');

    function clearAddForm() {
        
        $('#empContractNew_emp_contract_number').val('');
        $('#empContractNew_keterangan').val('');
        $('#empContractNew_emp_contract_start_date').val(displayDateFormat);
        $('#empContractNew_emp_contract_end_date').val(displayDateFormat);
     
        $('div#addPaneEmpContractNew label.error').hide();
        $('div#messagebarEmpContractNew').hide();
    }

    function addEditLinks() {
        removeEditLinks();
        $('#empContractNew_list tbody td.empContractNumber').wrapInner('<a href="#addPaneEmpContractNew"/>');
    }

    function removeEditLinks() {
        $('#empContractNew_list tbody td.empContractNumber a').each(function(index) {
            $(this).parent().text($(this).text());
        });
    }
    function extend(ContractNumber)
        {
            var endDate = $("#emp_contract_end_date_" + ContractNumber).val();
            var keterangan = $("#keterangan_" + ContractNumber).val();
              
            $('#empContractNew_emp_contract_number').val('');
            $('#empContractNew_keterangan').val(keterangan);
            $('#empContractNew_emp_contract_start_date').val(endDate);

            if ($.trim(endDate) == '') {
                endDate = displayDateFormat;
            }
            $('#empContractNew_emp_contract_start_date').val(endDate);

            $('div#messagebarEmpContractNew').hide();
            
            // hide validation error messages

            $('#empContractNewListActions').hide();
            $('#empContractNew_list .check#chkEmpContractNew').hide();
            $('#addPaneEmpContractNew').css('display', 'block');

            $(".paddingLeftRequired").show();
            $('#btnCancelEmpContractNew').show();

        }
       function endContract(ContractNumber)
       {
            $('#emp_contract_number').val(ContractNumber);
       }
      $(document).ready(function() {
  
        $('#addPaneEmpContractNew').hide();
        <?php  if (!$haveContracts){?>
        $(".check#chkEmpContractNew").hide();
        <?php } ?>
        
        $("#checkAllEmpContractNew").click(function(){
            if($("#checkAllEmpContractNew:checked").length > 0) {
                $(".checkbox#checkEmpContractNew").prop('checked', true);
            } else {
                $(".checkbox#checkEmpContractNew").prop('checked', false);
            }
            
            if($("#checkEmpContractNew:checked").length > 0) {
                $('#delEmpContractNewBtn').removeAttr('disabled');
                $('#delEmpContractNewBtn').css('background-color','#aa4935');
            } else {
                $('#delEmpContractNewBtn').attr('disabled', 'disabled');
                $('#delEmpContractNewBtn').css('background-color','#DDD');
            }
        });

        $(".checkbox#checkEmpContractNew").click(function() {
            $("#checkAllEmpContractNew").prop('checked', false);
            if(($(".checkbox#checkEmpContractNew").length) == $("#checkEmpContractNew:checked").length) {
                $("#checkAllEmpContractNew").prop('checked', true);
            }
            
            if($("#checkEmpContractNew:checked").length > 0) {
                $('#delEmpContractNewBtn').removeAttr('disabled');
                $('#delEmpContractNewBtn').css('background-color','#aa4935');
            } else {
                $('#delEmpContractNewBtn').attr('disabled', 'disabled');
                $('#delEmpContractNewBtn').css('background-color','#DDD');
            }
        });

        // Edit a emergency contact in the list
        $(document).on('click', '#frmEmpDelContractNew a', function() {
            
            $("#employeeContractNewHeading").text("<?php echo __js("Edit Contract");?>");
            var row = $(this).closest("tr");
            var ContractNumber = row.find('input.checkbox#checkEmpContractNew:first').val();
           
            var startDate = $("#emp_contract_start_date_" + ContractNumber).val();
            var endDate = $("#emp_contract_end_date_" + ContractNumber).val();
            var keterangan = $("#keterangan_" + ContractNumber).val();
              
            $('#empContractNew_emp_contract_number').val(ContractNumber);
            $('#empContractNew_keterangan').val(keterangan);
            $('#empContractNew_emp_contract_start_date').val(startDate);
            $('#empContractNew_emp_contract_end_date').val(endDate);

            if ($.trim(startDate) == '') {
                startDate = displayDateFormat;
            }
            $('#empContractNew_emp_contract_start_date').val(startDate);

            if ($.trim(endDate) == '') {
                endDate = displayDateFormat;
            }
            $('#empContractNew_emp_contract_end_date').val(endDate);

            $('div#messagebarEmpContractNew').hide();
            
            // hide validation error messages

            $('#empContractNewListActions').hide();
            $('#empContractNew_list .check#chkEmpContractNew').hide();
            $('#addPaneEmpContractNew').css('display', 'block');

            $(".paddingLeftRequired").show();
            $('#btnCancelEmpContractNew').show();

        });

        // Cancel in add pane
        $('#btnCancelEmpContractNew').click(function() {
            clearAddForm();
            $('#addPaneEmpContractNew').css('display', 'none');
            $('#empContractNewListActions').show();
            $('#empContractNew_list .check#chkEmpContractNew').show();
            <?php if ($empContractNewPermissions->canUpdate()){?>
            addEditLinks();
            <?php }?>
            $('div#messagebarEmpContractNew').hide();
            $(".paddingLeftRequired").hide();
        });

        $('#btnAddEmpContractNew').click(function() {
            $("#employeeContractNewHeading").text("<?php echo __js("Add Contract");?>");
            clearAddForm();

            // Hide list action buttons and checkbox
            $('#empContractNewListActions').hide();
            $('#empContractNew_list .check#chkEmpContractNew').hide();
            removeEditLinks();
            $('div#messagebarEmpContractNew').hide();
            
            $('#addPaneEmpContractNew').css('display', 'block');

            $(".paddingLeftRequired").show();

        });

        /* Valid Contact Phone */
        $.validator.addMethod("validContactPhone", function(value, element) {

            if ( $('#empContractNew_homePhone').val() == '' && $('#empContractNew_mobilePhone').val() == '' &&
                    $('#empContractNew_workPhone').val() == '' )
                return false;
            else
                return true
        });
        
        $("#frmEmpContractNew").validate({
            rules: {
                'empContractNew[keterangan]' : {required:false},
                'empContractNew[emp_contract_start_date]' : { required: true, valid_date: function(){ return {format:datepickerDateFormat, required:true, displayFormat:displayDateFormat}}},
                'empContractNew[emp_contract_end_date]' : 
                { 
                    required: true, 
                    valid_date: function()
                    { 
                        return {
                            format:datepickerDateFormat, 
                            required:true, 
                            displayFormat:displayDateFormat
                        } 
                    }, date_range: function() 
                    {
                        return {
                            format:datepickerDateFormat,
                            displayFormat:displayDateFormat,
                            fromDate:$('#empContractNew_emp_contract_start_date').val()
                        }
                    }
                }  
            },
            messages: {
                'empContractNew[keterangan]': {
                    required:'<?php echo __js(ValidationMessages::REQUIRED) ?>'
                }, 
                'empContractNew[emp_contract_start_date]': {
                    valid_date: lang_invalidDate, 
                    required:'<?php echo __js(ValidationMessages::REQUIRED) ?>'
                },
                'empContractNew[emp_contract_end_date]' : {
                    valid_date: lang_validDateMsg, 
                    date_range: lang_startDateAfterEndDate,
                    required:'<?php echo __js(ValidationMessages::REQUIRED) ?>'
                }
            }
        });

        $('#dialogConfirmEndContract').click(function(){
            if($('#frmEmpEndContractNew').valid()){
                $('#frmEmpEndContractNew').submit()
            }
        });
        $('#dialogCancelEndContract').click(function(){
            clearErrors();
        });
        $('#delEmpContractNewBtn').click(function() {
            
            var checked = $('#frmEmpDelContractNew input:checked').length;

            if (checked == 0) {
                $('div#messagebarEmpContractNew').show();
                $("#messagebarEmpContractNew").attr('class', "messageBalloon_notice");
                $("#messagebarEmpContractNew").text('<?php echo __js(TopLevelMessages::SELECT_RECORDS); ?>');
            } else {
                $('#frmEmpDelContractNew').submit();
            }
        });

        $('#btnSaveEmpContractNew').click(function() {
            $('#frmEmpContractNew').submit();
        });
});
</script>
