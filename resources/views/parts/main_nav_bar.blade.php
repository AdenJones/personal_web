
@if (count($projects) > 0)
<ul class="bxslider">
   @foreach ($projects as $project)
   <li>
      <picture>
      <source srcset="{{url('/')}}/images/{{$project->slider_image}}" media="(min-width: 560px)">
      <img src="{{url('/')}}/images/{{$project->slider_image_small}}" alt="{{$project->name}}">
      </picture>
      <div class="slider_floating_info">
         <div class="slider_outer">
            <div class="slider_container">
               <h1><a href="{{url('/project')}}/{{$project->id}}">{{$project->name}}</a></h1>
            </div>
         </div>
      </div>
   </li>
   @endforeach
  
</ul>
<script type="text/javascript">
$(document).ready(function(){
  $('.bxslider').bxSlider({
   slideWidth: 1100,
   auto: true,
   autoStart: true,
   autoDelay: 1000,
   adaptiveHeight: true
  });
});
</script>
@else
    <h1>No projects at this time! <a href="{{ url('/projects')}}">Add Project</a></h1>
@endif
