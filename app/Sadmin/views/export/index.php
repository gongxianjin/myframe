<body style="overflow-x: hidden;background-color: #ededf0">
<section class="content QXGL">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="#">报表管理</a></label>
    </div>
    <div class="navBox QXGLBox">

<!--        <div class="tableBox">-->
<!--            <div class="searchBox">-->
<!--                <form class="form-horizontal son exportv1giftrecord">-->
<!--                    <label style="width: 13%">时间段：</label>-->
<!--                    <fieldset>-->
<!--                        <div class="control-group">-->
<!--                            <div class="controls">-->
<!--                                <div class="input-prepend input-group">-->
<!--                                    <span class="add-on input-group-addon">-->
<!--                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>-->
<!--                                    </span>-->
<!--                                    <input type="text" readonly  name="starttime" class="form-control birthday" value="--><?php //echo date('Y-m-d',time())?><!--" />-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </fieldset>-->
<!--                    <label style="width: 3%">-</label>-->
<!--                    <fieldset>-->
<!--                        <div class="control-group">-->
<!--                            <div class="controls">-->
<!--                                <div class="input-prepend input-group">-->
<!--                                    <span class="add-on input-group-addon">-->
<!--                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>-->
<!--                                    </span>-->
<!--                                    <input type="text" readonly name="endtime" class="form-control birthday" value="--><?php //echo date('Y-m-d',time())?><!--" />-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </fieldset>-->
<!--                </form>-->
<!--                <button class="btn btn-info" id="exportv1giftrecord" pos="--><?php //echo __SADMIN__?><!--/Export/exportv1giftrecord">导出V1版烟包抽奖数据报表</button>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <div class="tableBox">-->
<!--            <div class="searchBox">-->
<!--                <form class="form-horizontal son exportv1barcodes">-->
<!--                    <label style="width: 13%">时间段：</label>-->
<!--                    <fieldset>-->
<!--                        <div class="control-group">-->
<!--                            <div class="controls">-->
<!--                                <div class="input-prepend input-group">-->
<!--                                    <span class="add-on input-group-addon">-->
<!--                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>-->
<!--                                    </span>-->
<!--                                    <input type="text" readonly  name="starttime" class="form-control birthday" value="--><?php //echo date('Y-m-d',time())?><!--" />-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </fieldset>-->
<!--                    <label style="width: 3%">-</label>-->
<!--                    <fieldset>-->
<!--                        <div class="control-group">-->
<!--                            <div class="controls">-->
<!--                                <div class="input-prepend input-group">-->
<!--                                    <span class="add-on input-group-addon">-->
<!--                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>-->
<!--                                    </span>-->
<!--                                    <input type="text" readonly name="endtime" class="form-control birthday" value="--><?php //echo date('Y-m-d',time())?><!--" />-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </fieldset>-->
<!--                </form>-->
<!--                <button class="btn btn-info" id="exportv1barcodes" pos="--><?php //echo __SADMIN__?><!--/Export/exportv1barcodes">导出V1版本接口规格查询报表</button>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <div class="tableBox">-->
<!--            <div class="searchBox">-->
<!--                <form class="form-horizontal son exportv1users">-->
<!--                    <label style="width: 13%">时间段：</label>-->
<!--                    <fieldset>-->
<!--                        <div class="control-group">-->
<!--                            <div class="controls">-->
<!--                                <div class="input-prepend input-group">-->
<!--                                    <span class="add-on input-group-addon">-->
<!--                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>-->
<!--                                    </span>-->
<!--                                    <input type="text" readonly  name="starttime" class="form-control birthday" value="--><?php //echo date('Y-m-d',time())?><!--" />-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </fieldset>-->
<!--                    <label style="width: 3%">-</label>-->
<!--                    <fieldset>-->
<!--                        <div class="control-group">-->
<!--                            <div class="controls">-->
<!--                                <div class="input-prepend input-group">-->
<!--                                    <span class="add-on input-group-addon">-->
<!--                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>-->
<!--                                    </span>-->
<!--                                    <input type="text" readonly name="endtime" class="form-control birthday" value="--><?php //echo date('Y-m-d',time())?><!--" />-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </fieldset>-->
<!--                </form>-->
<!--                <button class="btn btn-info" id="exportv1users" pos="--><?php //echo __SADMIN__?><!--/Export/exportv1users">导出V1充值归属地数据报表</button>-->
<!--            </div>-->
<!--        </div>-->

        <div class="tableBox">
            <div class="searchBox">
                <form class="form-horizontal son exportv2giftrecord">
                    <label style="width: 13%">时间段：</label>
                    <fieldset>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" readonly  name="starttime" class="form-control birthday" value="<?php echo date('Y-m-d',time())?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <label style="width: 3%">-</label>
                    <fieldset>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" readonly name="endtime" class="form-control birthday" value="<?php echo date('Y-m-d',time())?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <button class="btn btn-info" id="exportv2giftrecord" pos="<?php echo __SADMIN__?>/Export/exportv2giftrecord">导出V2版烟包抽奖数据报表</button>
            </div>
        </div>


        <div class="tableBox">
            <div class="searchBox">
                <form class="form-horizontal son exportv2barcodes">
                    <label style="width: 13%">时间段：</label>
                    <fieldset>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" readonly  name="starttime" class="form-control birthday" value="<?php echo date('Y-m-d',time())?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <label style="width: 3%">-</label>
                    <fieldset>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" readonly name="endtime" class="form-control birthday" value="<?php echo date('Y-m-d',time())?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <button class="btn btn-info" id="exportv2barcodes" pos="<?php echo __SADMIN__?>/Export/exportv2barcodes">导出V2版本接口规格查询报表</button>
            </div>
        </div>

        <div class="tableBox">
            <div class="searchBox">
                <form class="form-horizontal son exportv2mobilebills">
                    <label style="width: 13%">时间段：</label>
                    <fieldset>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" readonly  name="starttime" class="form-control birthday" value="<?php echo date('Y-m-d',time())?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <label style="width: 3%">-</label>
                    <fieldset>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" readonly name="endtime" class="form-control birthday" value="<?php echo date('Y-m-d',time())?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <button class="btn btn-info" id="exportv2mobilebills" pos="<?php echo __SADMIN__?>/Export/exportv2mobilebills">导出V2版本充值数据报表</button>
            </div>
        </div>

    </div>

</section>

<script src="<?php echo __SADMIN_BASE__?>/javascript/lib/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="<?php echo __SADMIN_BASE__?>/javascript/lib/moment.js"></script>
<script type="text/javascript" src="<?php echo __SADMIN_BASE__?>/javascript/lib/daterangepicker.js"></script>

<script>

    /*日期选择插件*/
    $('.birthday').daterangepicker({
        timePicker : false, //是否显示小时和分钟
        timePickerIncrement : 60, //时间的增量，单位为分钟
        timePicker12Hour : false, //是否使用12小时制来显示时间
        singleDatePicker: true,
        format : 'YYYY-MM-DD', //控件中from和to 显示的日期格式
    }, function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });

    $('form').submit(function(){return false;});

    //exportv1giftrecord导出功能
    $("#exportv1giftrecord").click(function(){
        var url = $(this).attr('pos');
        var query  = $('.exportv1giftrecord').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        query = query.replace(/[\\&=]/g,"/");
        url = url+'/'+query;
        if(url=='') return false;
        window.location.href = url;
    });

    //exportv1barcodes导出功能
    $("#exportv1barcodes").click(function(){
        var url = $(this).attr('pos');
        var query  = $('.exportv1barcodes').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        query = query.replace(/[\\&=]/g,"/");
        url = url+'/'+query;
        if(url=='') return false;
        window.location.href = url;
    });

    //exportv1users导出功能
    $("#exportv1users").click(function(){
        var url = $(this).attr('pos');
        var query  = $('.exportv1users').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        query = query.replace(/[\\&=]/g,"/");
        url = url+'/'+query;
        if(url=='') return false;
        window.location.href = url;
    });


    //exportv1giftrecord导出功能
    $("#exportv2giftrecord").click(function(){
        var url = $(this).attr('pos');
        var query  = $('.exportv2giftrecord').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        query = query.replace(/[\\&=]/g,"/");
        url = url+'/'+query;
        if(url=='') return false;
        window.location.href = url;
    });

    //exportv1barcodes导出功能
    $("#exportv2barcodes").click(function(){
        var url = $(this).attr('pos');
        var query  = $('.exportv2barcodes').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        query = query.replace(/[\\&=]/g,"/");
        url = url+'/'+query;
        if(url=='') return false;
        window.location.href = url;
    });

    //exportv2mobilebills导出功能
    $("#exportv2mobilebills").click(function(){
        var url = $(this).attr('pos');
        var query  = $('.exportv2mobilebills').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        query = query.replace(/[\\&=]/g,"/");
        url = url+'/'+query;
        if(url=='') return false;
        window.location.href = url;
    });

</script>