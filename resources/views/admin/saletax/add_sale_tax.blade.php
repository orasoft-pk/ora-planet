@extends('layouts.admin')

@section('styles')

<link href="{{asset('assets/admin/css/jquery.tagit.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/admin/css/jquery-ui.css')}}" rel="stylesheet" type="text/css">

<style type="text/css">
    .colorpicker-alpha {display:none !important;}
    .colorpicker{ min-width:128px !important;}
    .colorpicker-color {display:none !important;}
    .add-product-box .form-horizontal .form-group:last-child {margin-bottom: 20px; }
    .nav-tabs a[aria-expanded="false"]::before, a[aria-expanded="true"]::before {
        content: '';
    }
</style>


@endsection

@section('content')
<div class="right-side">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <!-- Starting of Dashboard area -->
                        <div class="section-padding add-product-1">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="add-product-box">
                                    <div class="product__header"  style="border-bottom: none;">
                                        <div class="row reorder-xs">
                                            <div class="col-lg-6 col-md-5 col-sm-5 col-xs-12">
                                                <div class="product-header-title">
                                                    <h2>Add Sale Tax <a href="{{ route('admin-prod-index') }}" style="padding: 5px 12px;" class="btn add-back-btn"><i class="fa fa-arrow-left"></i> Back</a></h2>
                                                    <p>Dashboard <i class="fa fa-angle-right" style="margin: 0 2px;"></i> Products <i class="fa fa-angle-right" style="margin: 0 2px;"></i> Add Sale Tax 
                                                </div>
                                            </div>
                                              @include('includes.notification')
                                        </div>   
                                    </div>
                                        <hr>
<div>
                                          @include('includes.form-error')
                                          @include('includes.form-success')
                                          
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li class="active"><a href="#physical" role="tab" data-toggle="tab"> Physical</a>
      </li>
     
    </ul>
    
    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane fade active in" id="physical">
                                        <form class="form-horizontal" action="{{route('insert-sale-tax')}}" method="POST" enctype="multipart/form-data" id="form1">
                                          {{csrf_field()}}
                                          @method('PATCH')
                                          
                               
                                          <div class="form-group">
                                            <label class="control-label col-sm-4" for="cat">Category*</label>
                                            <div class="col-sm-6"> 
                                            <select class="form-control" id="cat" name="category_id" required="">
                                                  <option value="">Select Category</option>
                                              @foreach($cats as $cat)
                                                  <option value="{{$cat->id}}" >{{$cat->cat_name}}</option>
                                              @endforeach
                                              </select>
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <label class="control-label col-sm-4" for="blood_group">Sub Category*</label>
                                            <div class="col-sm-6"> 
                                            <select class="form-control" name="subcategory_id" id="subcat" disabled="true">
                                                  <option value="" >Select Sub Category</option>
                                              </select>
                                            </div>
                                          </div>

                                          <div class="form-group">
                                            <label class="control-label col-sm-4" for="blood_group">Child Category*</label>
                                            <div class="col-sm-6"> 
                                            <select class="form-control" name="childcategory_id" id="childcat"  disabled="true">
                                                  <option value="" >Select Child Category</option>
                                              </select>
                                            </div>
                                          </div>

                                          <div class="form-group">
                                            <label class="control-label col-sm-4" for="blood_group_display_name">Sale Tax* <span>(only numbers)</span></label>
                                            <div class="col-sm-6">
                                              <input class="form-control" name="sales_tax" id="blood_group_display_name" placeholder="Enter Sale Tax" required="" type="number" >
                                            </div>
                                          </div>

                                         
                                        
                                       
    </div>
                           
                                           
                                        
                                            <div class="add-product-footer">
                                                <button name="addProduct_btn" type="submit" class="btn add-product_btn">Submit</button>
                                            </div>
                                        </form>
      </div>
    </div>

</div>

                                    </div>
                                </div>
                        </div>
                    </div>
                    <!-- Ending of Dashboard area --> 
                </div>
            </div>
        </div>
    </div>
<!-- Gallry Modal1 -->
<div id="myModal1" class="modal fade gallery" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Image Gallery</h4>
      </div>
      <div class="modal-body">
        <div class="gallery-btn-area text-center">
            <a style="cursor: pointer;" class="btn btn-info gallery-btn mr-5" id="prod_gallery1"><i class="fa fa-download"></i> Upload Images</a>
            <a style="cursor: pointer; background: #009432;" class="btn btn-info gallery-btn mr-5" data-dismiss="modal"><i class="fa fa-check" ></i> Done</a>
            <p style="font-size: 11px;">You can upload multiple images.</p>
        </div>

        <div class="gallery-wrap" id="gallery-wrap1">
                <div class="row">
                </div>
        </div>
      </div>
    </div>

  </div>
</div>
<div id="myModal2" class="modal fade gallery" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Image Gallery</h4>
      </div>
      <div class="modal-body">
        <div class="gallery-btn-area text-center">
            <a style="cursor: pointer;" class="btn btn-info gallery-btn mr-5" id="prod_gallery2"><i class="fa fa-download"></i> Upload Images</a>
            <a style="cursor: pointer; background: #009432;" class="btn btn-info gallery-btn mr-5" data-dismiss="modal"><i class="fa fa-check" ></i> Done</a>
            <p style="font-size: 11px;">You can upload multiple images.</p>
        </div>

        <div class="gallery-wrap" id="gallery-wrap2">
                <div class="row">
                </div>
        </div>
      </div>
    </div>

  </div>
</div>
<div id="myModal3" class="modal fade gallery" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Image Gallery</h4>
      </div>
      <div class="modal-body">
        <div class="gallery-btn-area text-center">
            <a style="cursor: pointer;" class="btn btn-info gallery-btn mr-5" id="prod_gallery3"><i class="fa fa-download"></i> Upload Images</a>
            <a style="cursor: pointer; background: #009432;" class="btn btn-info gallery-btn mr-5" data-dismiss="modal"><i class="fa fa-check" ></i> Done</a>
            <p style="font-size: 11px;">You can upload multiple images.</p>
        </div>

        <div class="gallery-wrap" id="gallery-wrap3">
                <div class="row">
                </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<style type="text/css">
  .nicEdit-main {
    width: 100% !important;
    min-height: 114px !important;
  }
</style>
<script type="text/javascript">
    $('.colorpicker-component').colorpicker();
    $('.colorpick').colorpicker();
</script>
<script type="text/javascript">
$("#check2").change(function() {
    if(this.checked) {
        $("#fimg").show();
    }
    else
    {
        $("#fimg").hide();

    }
});
</script>

<script type="text/javascript">
$("#type_check").change(function() {
    var val = $(this).val();
    if(val == 1)
    {
      $('#link').hide();
      $('#file').show();
    }
    else{
      $('#link').show();
      $('#file').hide();      
    }
});
$("#type_check1").change(function() {
    var val = $(this).val();
    if(val == 1)
    {
      $('#link1').hide();
      $('#file1').show();
    }
    else{
      $('#link1').show();
      $('#file1').hide();      
    }
});
$("#check3").change(function() {
    if(this.checked) {
        $("#fimg1").show();
    }
    else
    {
        $("#fimg1").hide();

    }
});
$("#check10").change(function() {
    if(this.checked) {
        $("#fimg2").show();
    }
    else
    {
        $("#fimg2").hide();

    }
});
$("#check11").change(function() {
    if(this.checked) {
        $("#fimg3").show();
    }
    else
    {
        $("#fimg3").hide();

    }
});
$("#check12").change(function() {
    if(this.checked) {
        $("#fimg4").show();
    }
    else
    {
        $("#fimg4").hide();

    }
});
$("#check122").change(function() {
    if(this.checked) {
        $("#fimg44").show();
    }
    else
    {
        $("#fimg44").hide();

    }
});
$("#check1222").change(function() {
    if(this.checked) {
        $("#fimg444").show();
    }
    else
    {
        $("#fimg444").hide();

    }
});
$("#check50").change(function() {
    if(this.checked) {
        $("#fimg50").show();
    }
    else
    {
        $("#fimg50").hide();

    }
});
$("#product_measure").change(function() {
    var val = $(this).val();
    $('#measurement').val(val);
    if(val == "Custom")
    {
    $('#measurement').val('');
      $('#measure').show();
    }
    else{
      $('#link').show();
      $('#measure').hide();      
    }
});
</script>

<script type="text/javascript" src="{{asset('assets/admin/js/nicEdit.js')}}"></script> 
<script type="text/javascript">
//<![CDATA[
        bkLib.onDomLoaded(function() {
            new nicEditor().panelInstance('digital_description');
            new nicEditor().panelInstance('digital_policy');
            new nicEditor().panelInstance('license_description');
            new nicEditor().panelInstance('license_policy');
            new nicEditor().panelInstance('profile_description');
            new nicEditor().panelInstance('policy');
            $('.nicEdit-panelContain').parent().width('100%');
            $('.nicEdit-panelContain').parent().next().width('98%');
        });
  //]]>




</script>

<script type="text/javascript">
  
  function uploadclick(){
    $("#uploadFile").click();
    $("#uploadFile").change(function(event) {
      readURL(this);
      $("#uploadTrigger").html($("#uploadFile").val());
  });
  }


  function readURL(input){
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#adminimg').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
}

  function uploadclick1(){
      $("#uploadFile1").click();
      $("#uploadFile1").change(function(event) {
            readURL1(this);
            $("#uploadTrigger1").html($("#uploadFile1").val());
      });

}

  function readURL1(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#adminimg1').attr('src', e.target.result);
                };
      reader.readAsDataURL(input.files[0]);
    }
}

        function uploadclick2(){
            $("#uploadFile2").click();
            $("#uploadFile2").change(function(event) {
                readURL2(this);
                $("#uploadTrigger2").html($("#uploadFile2").val());
            });

        }

        function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

  function uploadclick3(){
      $("#uploadFile3").click();
      $("#uploadFile3").change(function(event) {
            readURL3(this);
            $("#uploadTrigger3").html($("#uploadFile3").val());
      });

}

  function readURL3(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#adminimg3').attr('src', e.target.result);
                };
      reader.readAsDataURL(input.files[0]);
    }
}

        function uploadclick4(){
            $("#uploadFile4").click();
            $("#uploadFile4").change(function(event) {
                readURL4(this);
                $("#uploadTrigger4").html($("#uploadFile4").val());
            });

        }

        function readURL4(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

</script>

<script type="text/javascript">

  $('#cat, #cat1, #cat2').on('change', function() {
    var cat = $(this).val();

      $('#subcat, #subcat1, #subcat2').html('<option >Select Sub Category</option>');
      $('#subcat, #subcat1, #subcat2').attr('disabled', true);  
      $('#childcat, #childcat1, #childcat2').html('<option >Select Child Category</option>');
      $('#childcat, #childcat1, #childcat2').attr('disabled', true);   
    
        $.ajax({
            type: "GET",
            url:"{{URL::to('json/subcats')}}",
            data:{id:cat},
            success:function(data){
                  $('#subcat, #subcat1, #subcat2').html('<option value="" >Select Sub Category</option>');

                   for(var k in data)
                    {
                      $('#subcat, #subcat1, #subcat2').append('<option  value="'+data[k]['id']+'">'+data[k]['sub_name']+'</option>');                      
                    } 
                    if(data != "")
                    {
                     $('#subcat, #subcat1, #subcat2').attr('disabled', false);                        
                    }
                
                }
              
      });      
    
});

  $('#subcat, #subcat1, #subcat2').on('change', function() {
    var subcat = $(this).val();

      $('#childcat, #childcat1, #childcat2').html('<option >Select Child Category</option>'); 
      $('#childcat, #childcat1, #childcat2').attr('disabled', true);  
        $.ajax({
            type: "GET",
            url:"{{URL::to('json/childcats')}}",
            data:{id:subcat},
            success:function(data){
                  $('#childcat, #childcat1, #childcat2').html('<option  value="">Select Child Category</option>');

                   for(var k in data)
                    {
                      $('#childcat, #childcat1, #childcat2').append('<option  value="'+data[k]['id']+'">'+data[k]['child_name']+'</option>');                      
                    } 
                    if(data != "")
                    {
                      $('#childcat, #childcat1, #childcat2').attr('disabled', false); 
                    }              
                }
              
      });      
    


});
</script>
  
<script type="text/javascript">
      $(document).on('click','#add-color',function() {

        $(".color-area").append('<div class="form-group single-color">'+
                ' <label class="control-label col-sm-4" for="blood_group_display_name">'+
                 ' Product Colors* <span>(Choose Your Favourite Color.)</span></label>'+
                  '<div class="col-sm-6">'+
                  '<div class="input-group colorpicker-component">'+
                '<input  type="text" name="color[]" value="#000000"  class="form-control colorpick"  />'+
                    '<span class="input-group-addon"><i></i></span>'+
                   '<span class="ui-close1">X</span>'+
                      '</div>'+
                   '</div>'+
                 '</div>');
            $('.colorpicker-component').colorpicker();
            $('.colorpick').colorpicker();

    });

  function isEmpty(el){
      return !$.trim(el.html())
  }


  $(document).on('click', '.ui-close1' ,function() {
    $(this.parentNode.parentNode.parentNode).hide();
    $(this.parentNode.parentNode.parentNode).remove();
    if (isEmpty($('#q1'))) {

        $(".color-area").append('<div class="form-group single-color">'+
                ' <label class="control-label col-sm-4" for="blood_group_display_name">'+
                 ' Product Colors* <span>(Choose Your Favourite Color.)</span></label>'+
                  '<div class="col-sm-6">'+
                  '<div class="input-group colorpicker-component">'+
                '<input  type="text" name="color[]" value="#000000"  class="form-control colorpick"  />'+
                    '<span class="input-group-addon"><i></i></span>'+
                   '<span class="ui-close1">X</span>'+
                      '</div>'+
                   '</div>'+
                 '</div>');

            $('.colorpicker-component').colorpicker();
            $('.colorpick').colorpicker();
    }
  });
</script>


<script type="text/javascript">
      $(document).on('click','#add-field-btn',function() {
        $(".qualification").append('<div class="qualification-area">'+
                '<div class="form-group">'+
                 '<div class="col-xs-12 col-sm-6">'+
                 '<label> Keyword: </label>'+
'<input type="text" class="form-control" name="features[]" placeholder="Keyword" required="">'+
                   '</div>'+                
                   '<div class="col-xs-12 col-sm-6">'+
                   '<label> Choose Your Color: </label>'+
                  '<div class="input-group colorpicker-component">'+
                '<input  type="text" name="colors[]" value="#000000"  class="form-control colorpick"  />'+
                    '<span class="input-group-addon"><i></i></span>'+
                  '<span class="ui-close">X</span>'+
                      '</div>'+
                    '</div>'+
                    '</div>'+
                  '</div>'+
                 '</div>');
            $('.colorpicker-component').colorpicker();
            $('.colorpick').colorpicker();

    });

  function isEmpty(el){
      return !$.trim(el.html())
  }


  $(document).on('click', '.ui-close' ,function() {
    $(this.parentNode.parentNode.parentNode.parentNode).hide();
    $(this.parentNode.parentNode.parentNode.parentNode).remove();
    if (isEmpty($('#q'))) {
        $(".qualification").append('<div class="qualification-area">'+
                '<div class="form-group">'+
                 '<div class="col-xs-12 col-sm-6">'+
                 '<label> Keyword: </label>'+
'<input type="text" class="form-control" name="features[]" placeholder="Keyword">'+
                   '</div>'+                
                   '<div class="col-xs-12 col-sm-6">'+
                   '<label> Choose Your Color: </label>'+
                  '<div class="input-group colorpicker-component">'+
                '<input  type="text" name="colors[]" value="#000000"  class="form-control colorpick"  />'+
                    '<span class="input-group-addon"><i></i></span>'+
                  '<span class="ui-close">X</span>'+
                      '</div>'+
                    '</div>'+
                    '</div>'+
                  '</div>'+
                 '</div>');
            $('.colorpicker-component').colorpicker();
            $('.colorpick').colorpicker();
    }
  });
</script>

<script type="text/javascript">
      $(document).on('click','#add-field-btn2',function() {
        $(".qualification2").append('<div class="qualification-area">'+
                '<div class="form-group">'+
                 '<div class="col-xs-12 col-sm-6">'+
                 '<label> Keyword: </label>'+
'<input type="text" class="form-control" name="features[]" placeholder="Keyword" required="">'+
                   '</div>'+                
                   '<div class="col-xs-12 col-sm-6">'+
                   '<label> Choose Your Color: </label>'+
                  '<div class="input-group colorpicker-component">'+
                '<input  type="text" name="colors[]" value="#000000"  class="form-control colorpick"  />'+
                    '<span class="input-group-addon"><i></i></span>'+
                  '<span class="ui-close2">X</span>'+
                      '</div>'+
                    '</div>'+
                    '</div>'+
                  '</div>'+
                 '</div>');
            $('.colorpicker-component').colorpicker();
            $('.colorpick').colorpicker();

    });

  function isEmpty(el){
      return !$.trim(el.html())
  }


  $(document).on('click', '.ui-close2' ,function() {
    $(this.parentNode.parentNode.parentNode.parentNode).hide();
    $(this.parentNode.parentNode.parentNode.parentNode).remove();
    if (isEmpty($('#q2'))) {

        $(".qualification2").append('<div class="qualification-area">'+
                '<div class="form-group">'+
                 '<div class="col-xs-12 col-sm-6">'+
                 '<label> Keyword: </label>'+
'<input type="text" class="form-control" name="features[]" placeholder="Keyword">'+
                   '</div>'+                
                   '<div class="col-xs-12 col-sm-6">'+
                   '<label> Choose Your Color: </label>'+
                  '<div class="input-group colorpicker-component">'+
                '<input  type="text" name="colors[]" value="#000000"  class="form-control colorpick"  />'+
                    '<span class="input-group-addon"><i></i></span>'+
                  '<span class="ui-close2">X</span>'+
                      '</div>'+
                    '</div>'+
                    '</div>'+
                  '</div>'+
                 '</div>');
            $('.colorpicker-component').colorpicker();
            $('.colorpick').colorpicker();
    }
  });
</script>

<script type="text/javascript">
      $(document).on('click','#add-field-btn3',function() {
        $(".qualification3").append('<div class="qualification-area">'+
                '<div class="form-group">'+
                 '<div class="col-xs-12 col-sm-6">'+
                 '<label> Keyword: </label>'+
'<input type="text" class="form-control" name="features[]" placeholder="Keyword" required="">'+
                   '</div>'+                
                   '<div class="col-xs-12 col-sm-6">'+
                   '<label> Choose Your Color: </label>'+
                  '<div class="input-group colorpicker-component">'+
                '<input  type="text" name="colors[]" value="#000000"  class="form-control colorpick"  />'+
                    '<span class="input-group-addon"><i></i></span>'+
                  '<span class="ui-close3">X</span>'+
                      '</div>'+
                    '</div>'+
                    '</div>'+
                  '</div>'+
                 '</div>');
            $('.colorpicker-component').colorpicker();
            $('.colorpick').colorpicker();

    });

  function isEmpty(el){
      return !$.trim(el.html())
  }


  $(document).on('click', '.ui-close3' ,function() {
    $(this.parentNode.parentNode.parentNode.parentNode).hide();
    $(this.parentNode.parentNode.parentNode.parentNode).remove();
    if (isEmpty($('#q3'))) {
        $(".qualification3").append('<div class="qualification-area">'+
                '<div class="form-group">'+
                 '<div class="col-xs-12 col-sm-6">'+
                 '<label> Keyword: </label>'+
'<input type="text" class="form-control" name="features[]" placeholder="Keyword">'+
                   '</div>'+                
                   '<div class="col-xs-12 col-sm-6">'+
                   '<label> Choose Your Color: </label>'+
                  '<div class="input-group colorpicker-component">'+
                '<input  type="text" name="colors[]" value="#000000"  class="form-control colorpick"  />'+
                    '<span class="input-group-addon"><i></i></span>'+
                  '<span class="ui-close3">X</span>'+
                      '</div>'+
                    '</div>'+
                    '</div>'+
                  '</div>'+
                 '</div>');
            $('.colorpicker-component').colorpicker();
            $('.colorpick').colorpicker();
    }
  });
</script>

<script type="text/javascript">
      $(document).on('click','#add-field-btn4',function() {
        $(".qualification4").append('<div class="qualification-area">'+
                '<div class="form-group">'+
                 '<div class="col-xs-12 col-sm-6">'+
                 '<label> License Key: </label>'+
'<input type="text" class="form-control" name="license[]" placeholder="License Key" required="">'+
                   '</div>'+               
                   '<div class="col-xs-12 col-sm-6">'+
                 '<label> License Quantity: </label>'+
                '<input type="number" name="license_qty[]" value="1"  class="form-control" min="1">'+
                  '<span class="ui-close4">X</span>'+
                    '</div>'+
                    '</div>'+
                  '</div>'+
                 '</div>');

    });

  function isEmpty(el){
      return !$.trim(el.html())
  }


  $(document).on('click', '.ui-close4' ,function() {
    $(this.parentNode.parentNode.parentNode).hide();
    $(this.parentNode.parentNode.parentNode).remove();
    if (isEmpty($('#q4'))) {
        $(".qualification4").append('<div class="qualification-area">'+
                '<div class="form-group">'+
                 '<div class="col-xs-12 col-sm-6">'+
                 '<label> License Key: </label>'+
'<input type="text" class="form-control" name="license[]" placeholder="License Key" required="">'+
                   '</div>'+               
                   '<div class="col-xs-12 col-sm-6">'+
                 '<label> License Quantity: </label>'+
                '<input type="number" name="license_qty[]" value="1"  class="form-control" min="1">'+
                  '<span class="ui-close4">X</span>'+
                    '</div>'+
                    '</div>'+
                  '</div>'+
                 '</div>');

    }
  });
</script>


<script src="{{asset('assets/admin/js/jqueryui.min.js')}}"></script>    
<script src="{{asset('assets/admin/js/tag-it.js')}}" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#size").tagit({
          fieldName: "size[]",
          allowSpaces: true 
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#myTags, #myTags1, #myTags2").tagit({
          fieldName: "tags[]",
          allowSpaces: true 
        });
    });

    $(document).ready(function() {
        $("#metaTags, #metaTags1, #metaTags2").tagit({
          fieldName: "meta_tag[]",
          allowSpaces: true 
        });
    });

// Gallery Section

  $(document).on('click', '.close1' ,function() {
    var id = $(this).find('input[type=hidden]').val();
    $('#galval1'+id).remove();
    $(this).parent().parent().remove();
  });

  $(document).on('click', '#prod_gallery1' ,function() {
    $('#uploadgallery1').click();
     $('#gallery-wrap1 .row').html('');
    $('#form1').find('.removegal1').val(0);
  });

  $("#uploadgallery1").change(function(){
     var total_file=document.getElementById("uploadgallery1").files.length;
     for(var i=0;i<total_file;i++)
     {
      $('#gallery-wrap1 .row').append('<div class="col-sm-4">'+
                                  '<div class="gallery__img">'+
                                  '<img src="'+URL.createObjectURL(event.target.files[i])+'" alt="gallery image">'+
                                  '<div class="gallery-close close1">'+
                                  '<input type="hidden" value="'+i+'">'+
                                  '<i class="fa fa-close"></i>'+
                                  '</div>'+
                                  '</div>'+
                                  '</div>');
      $('#form1').append('<input type="hidden" name="galval[]" id="galval1'+i+'" class="removegal1" value="'+i+'">')
     }

  });

  $(document).on('click', '.close2' ,function() {
    var id = $(this).find('input[type=hidden]').val();
    $('#galval2'+id).remove();
    $(this).parent().parent().remove();
  });

  $(document).on('click', '#prod_gallery2' ,function() {
    $('#uploadgallery2').click();
     $('#gallery-wrap2 .row').html('');
    $('#form2').find('.removegal2').val(0);
  });
  
  $("#uploadgallery2").change(function(){
     var total_file=document.getElementById("uploadgallery2").files.length;
     for(var i=0;i<total_file;i++)
     {
      $('#gallery-wrap2 .row').append('<div class="col-sm-4">'+
                                  '<div class="gallery__img">'+
                                  '<img src="'+URL.createObjectURL(event.target.files[i])+'" alt="gallery image">'+
                                  '<div class="gallery-close close2">'+
                                  '<input type="hidden" value="'+i+'">'+
                                  '<i class="fa fa-close"></i>'+
                                  '</div>'+
                                  '</div>'+
                                  '</div>');
      $('#form2').append('<input type="hidden" name="galval[]" id="galval2'+i+'" class="removegal2" value="'+i+'">')
     }

  });

  $(document).on('click', '.close3' ,function() {
    var id = $(this).find('input[type=hidden]').val();
    $('#galval3'+id).remove();
    $(this).parent().parent().remove();
  });

  $(document).on('click', '#prod_gallery3' ,function() {
    $('#uploadgallery3').click();
    $('#gallery-wrap3 .row').html('');
    $('#form3').find('.removegal3').val(0);
  });
  
  $("#uploadgallery3").change(function(){
     var total_file=document.getElementById("uploadgallery3").files.length;
     for(var i=0;i<total_file;i++)
     {
      $('#gallery-wrap3 .row').append('<div class="col-sm-4">'+
                                  '<div class="gallery__img">'+
                                  '<img src="'+URL.createObjectURL(event.target.files[i])+'" alt="gallery image">'+
                                  '<div class="gallery-close close3">'+
                                  '<input type="hidden" value="'+i+'">'+
                                  '<i class="fa fa-close"></i>'+
                                  '</div>'+
                                  '</div>'+
                                  '</div>');
      $('#form3').append('<input type="hidden" name="galval[]" id="galval3'+i+'" class="removegal3" value="'+i+'">')
     }

  });

</script>



@endsection