@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/static/faq.css') }}">
@endpush
@push('scripts')
  <script type="text/javascript" src={{url("js/faq.js")}} defer></script>
@endpush

@section('content')

<div class="container-fluid ">

  <div class ="row"> 

  <div class="col-2 col-sm-12 col-md-6 col-lg-4 col-xl-2 options p-5">
      <div class="row">
      <div class="col-12 header-options ">
        <h2>Faq Topics </h2>
      </div>
        <div class = "col-12 header-options">
          <button type="button" class="btn all btn-link text-start">All</button>
        </div>
        <div class = "col-12 header-options">
          <button type="button" class="btn general btn-link text-start">General Information</button>
        </div>
        <div class = "col-12 header-options">
        <button type="button" class="btn functionalities btn-link text-start">Functionalities</button>
        </div>
        <div class = "col-12 header-options">
        <button type="button" class="btn navigation btn-link text-start">Navigation</button>
        </div>
        <div class = "col-12 header-options">
          <button type="button" class="btn pricing  btn-link text-start">Pricing</button>
        </div> 
        </div>

    </div>

    <div class ="col p-5">

      <div class="row mb-2">
        <div>
          <header>
            <h1>Frequently Asked Questions</h1>
          </header>
        </div>
      </div>

      <div class="row mb-2">
        <div class = "col">
          <header>
              <h2 class ="option">All</h2>
          </header>
        </div>
        <div class="col  d-flex justify-content-end p-0">
          <button button type="button" class="btn showinfo p-3">Show all</button>
        </div>
      </div>

      <div class="accordion" id="accordion">

       <div class="accordion-item general p-4">

          <h2 class="accordion-header">
            <p class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
              What is project management? 
            </p>
          </h2>

      <div id="panelsStayOpen-collapseOne" class="accordion-collapse  collapse show">
        <div class="accordion-body">
          Project management is the practice of applying knowledge, skills, tools, and techniques to complete a project according to specific requirements. The objective of project management is to produce a complete project in a cert amount of time. This project needs to comply with the user needs.
        </div>
      </div>

      </div>

      <div class="accordion-item general p-4">

        <h2 class="accordion-header">
          <p class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
          What is a task in a project?
        </p>
        </h2>

        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
          <div class="accordion-body">
          The task is a single unit of work about the project. This task must have a deadline and may have sub-tasks associated with it. By completing task by task, the project becomes closer to be completed.
          </div>
        </div>

      </div>

      <div class="accordion-item general p-4">

        <h2 class="accordion-header">
          <p class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
            What is a tag for a project or task?
          </p>
        </h2>

        <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
          <div class="accordion-body">
          The primary role of tags is to delineate and characterize the overarching theme of a project, task, or any element encompassed within it.
          </div>
        </div>

      </div>
      <div class="accordion-item functionalities p-4">

        <h2 class="accordion-header">
          <p class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
           Who can see my profile?
          </p>
        </h2>

        <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse">
          <div class="accordion-body">
          Your profile is only accessible to you and the admins of the website.
          </div>
        </div>
      
      </div>

      <div class="accordion-item functionalities p-4">

      <h2 class="accordion-header">
        <p class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
          Who owns/controls the projects?
        </p>
      </h2>

      <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse">
        <div class="accordion-body">
          When a user creates a project, he becomes a coordinator for that project. This user can add another coordinators that have access to the same functionalities. 
          The coordinators of a project have access to management functionalities.
        </div>
      </div>

      </div>
      <div class="accordion-item pricing p-4">
        <h2 class="accordion-header">
          <p class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSix" aria-expanded="false" aria-controls="panelsStayOpen-collapseSix">
            Is ProjPlanner free?
          </p>
        </h2>

        <div id="panelsStayOpen-collapseSix" class="accordion-collapse collapse">
          <div class="accordion-body">
            Yes. Our services are completely <strong> free </strong>.
          </div>
      </div>

      </div>
  </div>
</div>
  </div>
  <div class = "row footer-content text-center">
      <span>Can't find your answers? <a href="{{ route('static',['page' => 'contacts'])}}">Contact us</a></span>
    </div>
</div>

@endsection
