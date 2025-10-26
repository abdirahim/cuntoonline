@extends('layouts.default')
@section('content')
    <div class="content cms">
      <div class="container">
      	<div class="row">
        	<div class="col-md-3">
			@include('elements.cms_left')
            </div>
            <div class="col-md-9">
            	<div class="cms-content  mt30 ">
                	<div class="page-heading">
                        <h4> {{ $result->title }}</h4>
                    </div>
                    <div class="about-text">
					{{ $result->accordingLanguage->source_col_description}}
                    </div>
                </div>
            </div>
        </div>
      </div>
  </div>   
@stop