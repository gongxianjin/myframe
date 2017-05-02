<body style="overflow-x: hidden;background-color: #ededf0">
<section class="content">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="<?php echo __SADMIN__?>/Prizeset/index">奖品管理</a>&nbsp;>&nbsp;<a href="#">奖品设置</a>&nbsp;>&nbsp;<a href="#">添加奖品</a></label>
    </div>
    <div class="addBox form_submit">
        <div class="inputBox radioBox">
            <p class="left">奖品规格：</p>
            <p class="right">
                <span>
                    <?php foreach($mlist as $item):?>
                    <label class="radio">
                        <input type="radio" name="gift_attr" id="optionsAttr1" value="<?php echo $item['id']?>"/>
                        <?php echo $item['materialsName']?>
                    </label>
                    <?php endforeach;?>
                </span>
            </p>
        </div>
        <div class="inputBox radioBox">
            <p class="left">奖品类型：</p>
            <p class="right">
                <span>
                    <label class="radio">
                        <input type="radio" name="gift_type" id="optionsType1" value="1" />
                        实物
                    </label>
                    <label class="radio">
                        <input type="radio" name="gift_type" id="optionsType2" value="2" />
                        娇子币
                    </label>
                    <label class="radio">
                        <input type="radio" name="gift_type" id="optionsType3" value="3" />
                        话费
                    </label>
                    <label class="radio">
                        <input type="radio" name="gift_type" id="optionsType4" value="4" />
                        红包
                    </label>
                </span>
            </p>
        </div>
        <div class="inputBox">
            <p class="left">奖品名称：</p>
            <p class="right">
                <input type="text" name="giftname" value=""  placeholder="请输入奖品名称">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">数值：</p>
            <p class="right">
                <input type="text" name="price" value=""   placeholder="请输入正整数">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">总个数：</p>
            <p class="right">
                <input type="text" name="total" value=""  placeholder="请输入正整数">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">每天发放上限：</p>
            <p class="right">
                <input type="text" name="daymax"  value=""  placeholder="请输入的正整数">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">每小时发放上限：</p>
            <p class="right">
                <input type="text" name="hourmax" value=""  placeholder="请输入的正整数">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">中奖概率：</p>
            <p class="right">
                <input type="text" name="rate" value=""  placeholder="请输入0-1的小数">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">每个openID总中奖上限：</p>
            <p class="right">
                <input type="text" name="openid_totalmax" value="" placeholder="请输入的正整数">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">每个openID每小时中奖上限：</p>
            <p class="right">
                <input type="text" name="openid_hourmax" value=""  placeholder="请输入的正整数">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">每个手机号总中奖上限：</p>
            <p class="right">
                <input type="text" name="phone_totalmax" value="" placeholder="请输入的正整数">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">每个手机号每小时中奖上限：</p>
            <p class="right">
                <input type="text" name="phone_hourmax" value="" placeholder="请输入的正整数" >
            </p>
        </div>
        <div class="inputBox">
            <p class="left">发放顺序：</p>
            <p class="right">
                <input type="text" name="order"  value="" placeholder="请输入1-20的正整数">
            </p>
        </div>
        <button class="btn btn-success sure ajax-post" type="button"  pos="<?php echo __SADMIN__?>/Prizeset/save_add">确认提交</button>
    </div>
</section>

<script src="<?php echo __SADMIN_BASE__?>/javascript/lib/jquery-2.1.1.min.js"></script>

<script>
    $(function(){
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