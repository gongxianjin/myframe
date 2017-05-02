<body style="overflow-x: hidden;background-color: #ededf0">
<section class="content">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="<?php echo __SADMIN__?>/Validformdate/index">有效时间管理</a>&nbsp;>&nbsp;<a href="#">添加有效时间</a></label>
    </div>
    <div class="addBox form_submit">
        <div class="inputBox">
            <p class="left" style="line-height: 34px">开始时间：</p>
            <div class="rightDiv ">
                <form class="form-horizontal son" style="padding-top: 0;line-height: 34px">
                    <fieldset>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" readonly  name="starttime" class="form-control birthday" value="<?php echo date('Y-m-d H:i:s',time())?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="inputBox">
            <p class="left" style="line-height: 34px">结束时间：</p>
            <div class="rightDiv ">
                <form class="form-horizontal son" style="padding-top: 0;line-height: 34px">
                    <fieldset>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" readonly  name="endtime" class="form-control birthday" value="<?php echo date('Y-m-d H:i:s',time())?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <button class="btn btn-success sure ajax-post" type="button" pos="<?php echo __SADMIN__?>/Validformdate/save_add">确认添加</button>
    </div>
</section>

<script src="<?php echo __SADMIN_BASE__?>/javascript/lib/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="<?php echo __SADMIN_BASE__?>/javascript/lib/moment.js"></script>
<script type="text/javascript" src="<?php echo __SADMIN_BASE__?>/javascript/lib/daterangepicker.js"></script>
<script>
    $(function(){

        $('#reservation').daterangepicker(null, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });

        /*日期选择插件*/
        $('.birthday').daterangepicker({
            timePicker : true, //是否显示小时和分钟
            timePickerIncrement : 30, //时间的增量，单位为分钟
            timePicker12Hour : false, //是否使用12小时制来显示时间
            singleDatePicker: true,
            format : 'YYYY-MM-DD HH:mm:ss', //控件中from和to 显示的日期格式
        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });

        $('.ajax-post').click(function(){
            pos = $(this).attr('pos');
            data = $('.form_submit').find('input').serialize();
            $.ajax({
                type:'POST',
                url:pos,
                data:data,
                dataType:"json",
                success:function(data){
                    if(data.code == 200){
                        alert(data.msg);
                        window.location.href = data.forward;
                    }else{
                        alert(data.msg);
                    }
                }
            });
        });

    })
</script>
</body>
</html>