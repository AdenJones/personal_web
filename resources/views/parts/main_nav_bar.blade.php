
@if (count($projects) > 0)
<ul class="bxslider">
   @foreach ($projects as $project)
   <li><img src="{{url('/')}}/images/{{$project->slider_image}}" alt="{{$project->name}}" /></li>
   @endforeach
  
</ul>
<script type="text/javascript">
$(document).ready(function(){
  $('.bxslider').bxSlider({
   slideWidth: 1100
  });
});
</script>
@else
    <h1>No projects at this time! <a href="{{ url('/projects')}}">Add Project</a></h1>
@endif
