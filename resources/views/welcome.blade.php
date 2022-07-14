@extends('layouts.main')

@section('content')
    <div class="container text-center welcome-content">
  <style>
  #sortable_images { list-style-type: none; margin: 0; padding: 0; width: 450px; overflow: hidden; }
  #sortable_images li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 100px; height: 90px; font-size: 4em; text-align: center; outline: 1px solid lightcoral; cursor: default; }
  </style>
  <script>
  $( function() {
    $( "#sortable_images" ).sortable();
    $( "#sortable_images" ).disableSelection();
  } );
  </script>
</head>
<body>
 
<ul id="sortable_images">
  <li class="ui-state-default">1</li>
  <li class="ui-state-default">2</li>
  <li class="ui-state-default">3</li>
  <li class="ui-state-default">4</li>
  <li class="ui-state-default">5</li>
  <li class="ui-state-default">6</li>
  <li class="ui-state-default">7</li>
  <li class="ui-state-default">8</li>
  <li class="ui-state-default">9</li>
  <li class="ui-state-default">10</li>
  <li class="ui-state-default">11</li>
  <li class="ui-state-default">12</li>
</ul>


<hr><hr><hr>

<style>
.box {
    /*display: -webkit-flex;*/
    display: flex;
    -webkit-flex-wrap: wrap;
    flex-wrap: wrap;
    /*width: 720px;*/
    padding: 40px;
    background-color: #fafafa;
}
.item {
    /*display: inline-block;*/
    margin: 6px;
    width: 120px;
    height: 120px;
    border: 1px solid #c4c4c4;
    box-shadow: 0 0 9px rgba(0, 0, 0, 0.13);
    line-height: 120px;
    text-align: center;
    font-size: 44px;
}
</style>

<div class="container">
    <div class="row">
        <div class="col-6">
            <div class="box grabbable-parent">
              <div class="item">1</div>
              <div class="item">2</div>
              <div class="item">3</div>
              <div class="item">4</div>
              <div class="item">5</div>
              <div class="item">6</div>
              <div class="item">7</div>
              <div class="item">8</div>
              <div class="item">9</div>
              <div class="item">10</div>
              <div class="item">11</div>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    document.querySelector(".grabbable-parent").grabbable()
    document.querySelector(".grabbable-parent").addEventListener('dragged', function(event) {
        log(event.target)
        $('.grabbable-parent .item').each(function() {
            log($(this).text())
        })
    })
})
</script>

    </div>
@endsection
