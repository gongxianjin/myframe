<body style="overflow-x: hidden;background-color: #ededf0">
<section class="content">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="<?php echo __SADMIN__?>/Material/index">规格管理</a>&nbsp;>&nbsp;<a href="#">修改规格</a></label>
    </div>
    <div class="addBox form_submit">
        <div class="inputBox">
            <p class="left">物料名称：</p>
            <p class="right">
                <input type="text" name="materialsName" placeholder="规格名" value="<?php echo $lists['materialsName']?>" />
            </p>
        </div>
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
                                    <input type="text" readonly  name="starttime" class="form-control birthday" value="<?php echo date('Y-m-d',($lists['starttime']/1000))?>" />
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
                                    <input type="text" readonly  name="endtime" class="form-control birthday" value="<?php echo date('Y-m-d',($lists['endtime']/1000))?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <input type="hidden" name="ids"  value="<?php echo $lists['id'];?>" />
        <button class="btn btn-success sure ajax-post" type="button" pos="<?php echo __SADMIN__?>/Material/save_edit">确认修改</button>
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
            singleDatePicker: true,format: 'YYYY-MM-DD'}, function(start, end, label) {
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