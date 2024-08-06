<x-app-layout>
@vite(['resources/sass/app.scss'])
<x-slot name="header">
   <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
       {{ __('Dashboard') }}
   </h2>
</x-slot>

<div class="py-12">
   <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
       <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
           <div class="p-6 text-gray-900 dark:text-gray-100">
               {{ __("You're logged in!") }}
           </div>
           <div class="p-6 text-gray-900 dark:text-gray-100">
               @if(!empty(session('success')))
                   <div class="alert alert-success">{{session('success')}}</div>
               @endif
           </div>
           <div class="card">
               <div class="card-body">
                   @if(!empty($errors->first()))
                       <span>{{$errors->first()}}</span>
                   @endif
                   <form action="{{route('upload')}}" method="post"  enctype="multipart/form-data">
                       <input type="file" accept=".csv" name="file">
                       <button>upload</button>
                   </form>
               </div>
           </div>
           @if(!empty($report))
               @foreach($report as $r)
                   <div class="alert alert-primary">
                       <span>User Type: {{$r->user_type}}</span><br>
                       <span>Operation Type: {{$r->operation_type}}</span><br>
                       <span>Amount: {{$r->amount}} {{$r->currency}}</span><br>
                       <span>Commission Fee: {{   convertCurrency($r->commission_fee,$r->currency) }} $</span><br>
                   </div>
               @endforeach
           @endif


       </div>
   </div>
</div>
</x-app-layout>
