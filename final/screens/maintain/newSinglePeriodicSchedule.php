<?php
/**
 * Created by PhpStorm.
 * User: poovarasan
 * Date: 22/8/15
 * Time: 1:37 PM
 */

?>

<div class='col-md-4 padding0 marginT10'>
    <div class='col-md-4 padding5 paddingT5 text-right'><label>Start Date</label></div>
    <div class='col-md-7 padding0 input-append date' id='dd1'><input type='text' name='newSingleCurrentDate'
                                                                     class='form-control' id='newSingleCurrentDate'
                                                                     value='<?php echo date('d-m-Y') ?>'
                                                                     readonly/></div>
</div>

<div class='col-md-4 marginT10 duration'>

    <div class='col-md-4 padding5 paddingT5 text-right'><label>End Date<label></div>
    <div class='col-md-7 padding0'><input type='text' name='newSingleEndDate' id='newSingleEndDate'
                                          class='form-control newSingleEndDate'
                                          readonly/><span class="add-on"><i
                class="glyphicon glyphicon-calendar"></i></span></div>

</div>

<div class='col-md-12 marginT10 duration'>
    <div class='col-md-6 padding0 marginT10'>
        <div class='col-md-4 padding5 paddingT5 text-right'><label>Frequency</label></div>
        <div class='col-md-7 padding0'><input type='number' name='newSingleNextFreqencyValue' value='0'
                                              id='newSingleNextFrequency' class='form-control'/></div>
    </div>
    <div class='col-md-6 padding0 marginT10'>
        <div class='col-md-4 padding5 paddingT5 text-right'><label>Select</label></div>
        <div class='col-md-7 padding0'>
            <select name='newSingleUnits' onchange='calculateValue(this)' id='newSingleUnits' style=' ' class='form-control'
                    required>
                <option value=''>Select Option</option>
                <option value='Week'>Week</option>
                <option value='Month'>Month</option>
                <option value='Year'>Year</option>
            </select>
        </div>
    </div>


    <div class='col-md-12 marginT10'>
        <div id='WeekMonth' class="marginT10"></div>
    </div>
    <div class="col-md-12 border-top maintenance-btm-ctl marginT10 text-center">
        <input type="button" value="View Scheduled Maintenance" class="btn btn-default btnviewschedule  "
               onclick="viewMaintainence()"/>
        <input type="button" value="Perform Tasks" class="btn btn-default btnperform"/>
        <input type="button" value="Save" id="" class="btn btn-default btnsave"
               onclick="saveData(this.id)"/>
        <input type="button" value="Clear" class="btn btn-default btnclr" onclick="clearValue()"/>
    </div>
</div>