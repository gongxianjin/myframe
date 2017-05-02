<body style="overflow-x: hidden;background-color: #ededf0">
<section class="content">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="<?php echo __SADMIN__?>/Prizeset/index">奖品管理</a>&nbsp;>&nbsp;<a href="#">奖品设置</a>&nbsp;>&nbsp;<a href="#">编辑奖品</a></label>
    </div>
    <div class="addBox form_submit">
        <div class="inputBox radioBox">
            <p class="left">奖品规格：</p>
            <p class="right">
                <span>
                    <?php foreach($mlist as $item):?>
                        <label class="radio">
                            <input type="radio" name="gift_attr" id="optionsAttr1" value="<?php echo $item['id']?>" <?php if($prize['gift_attr'] == $item['id']):?> checked <?php endif;?> disabled/>
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
                        <input type="radio" name="gift_type" id="optionsType1" value="1" <?php if($prize['gift_type'] == 1):?> checked <?php endif;?> disabled/>
                        实物
                    </label>
                    <label class="radio">
                        <input type="radio" name="gift_type" id="optionsType2" value="2" <?php if($prize['gift_type'] == 2):?> checked <?php endif;?> disabled/>
                        娇子币
                    </label>
                    <label class="radio">
                        <input type="radio" name="gift_type" id="optionsType3" value="3" <?php if($prize['gift_type'] == 3):?> checked <?php endif;?> disabled/>
                        话费
                    </label>
                    <label class="radio">
                        <input type="radio" name="gift_type" id="optionsType4" value="4" <?php if($prize['gift_type'] == 4):?> checked <?php endif;?> disabled/>
                        红包
                    </label>
                </span>
            </p>
        </div>
        <div class="inputBox">
            <p class="left">奖品名称：</p>
            <p class="right">
                <input type="text" name="giftname" value="<?php echo $prize['giftname']?>"  placeholder="请输入奖品名称" disabled>
            </p>
        </div>
        <div class="inputBox">
            <p class="left">数值：</p>
            <p class="right">
                <input type="text" name="price" value="<?php echo $prize['price']?>"   placeholder="请输入正整数" disabled>
            </p>
        </div>
        <div class="inputBox">
            <p class="left">总个数：</p>
            <p class="right">
                <input type="text" name="total" value="<?php echo $prize['total']?>"  placeholder="请输入正整数">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">每天发放上限：</p>
            <p class="right">
                <input type="text" name="daymax"  value="<?php echo $prize['daymax']?>"  placeholder="请输入的正整数">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">每小时发放上限：</p>
            <p class="right">
                <input type="text" name="hourmax" value="<?php echo $prize['hourmax']?>"  placeholder="请输入的正整数">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">中奖概率：</p>
            <p class="right">
                <input type="text" name="rate" value="<?php echo $prize['rate']?>"  placeholder="请输入0-1的小数">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">每个openID总中奖上限：</p>
            <p class="right">
                <input type="text" name="openid_totalmax" value="<?php echo $prize['openid_totalmax']?>" placeholder="请输入的正整数">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">每个openID每小时中奖上限：</p>
            <p class="right">
                <input type="text" name="openid_hourmax" value="<?php echo $prize['openid_hourmax']?>"  placeholder="请输入的正整数">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">每个手机号总中奖上限：</p>
            <p class="right">
                <input type="text" name="phone_totalmax" value="<?php echo $prize['phone_totalmax']?>" placeholder="请输入的正整数">
            </p>
        </div>
        <div class="inputBox">
            <p class="left">每个手机号每小时中奖上限：</p>
            <p class="right">
                <input type="text" name="phone_hourmax" value="<?php echo $prize['phone_hourmax']?>" placeholder="请输入的正整数" >
            </p>
        </div>
        <div class="inputBox">
            <p class="left">发放顺序：</p>
            <p class="right">
                <input type="text" name="order"  value="<?php echo $prize['order']?>" placeholder="请输入1-20的正整数">
            </p>
        </div>
        <input type="hidden" value="<?php echo $prize['id']?>" name="ids"/>
        <button class="btn btn-success sure ajax-post" type="button"  pos="<?php echo __SADMIN__?>/Prizeset/save_edit">确认提交</button>
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