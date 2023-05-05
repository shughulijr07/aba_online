@extends('layouts.administrator.admin')


@section('content')
    <div class="container">
      <div class="mt-5 ">
         <h1>Request Details</h1>
      </div>
      <hr>

      <div class="row ">
         <div class="col-lg-12 mx-auto">
            <div class="card mt-2 mx-auto p-4 bg-light">
               <div class="card-body bg-light">

                  <div class="container">
                     <form id="contact-form" role="form">
                        <div class="controls">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group row">
                                    <label for="email" class="col-sm-3 col-md-3 col-form-label">
                                       Subject
                                    </label>
                                    <div class="col-sm-9 col-md-9">
                                       <input type="email" class="form-control" id="email" placeholder="Enter case subject" required>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group row">
                                    <label for="email" class="col-sm-3 col-md-3 col-form-label">
                                       Product Category
                                    </label>
                                    <div class="col-sm-9 col-md-9">
                                       <select name="" id="" class="form-control">
                                          <option value="">-- None --</option>
                                          <option value="">Category 1</option>
                                          <option value="">Category 2</option>
                                          <option value="">Category 3</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group row">
                                    <label for="email" class="col-sm-3 col-md-3 col-form-label">
                                       Product
                                    </label>
                                    <div class="col-sm-9 col-md-9">
                                       <select name="" id="" class="form-control" disabled>
                                          <option value="">--None--</option>
                                          <option value="">Desktop</option>
                                          <option value="">Laptop</option>
                                          <option value="">Printer</option>
                                          <option value="">Scanner</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group row">
                                    <label for="email" class="col-sm-3 col-md-3 col-form-label">
                                       Product Version
                                    </label>
                                    <div class="col-sm-9 col-md-9">
                                       <select name="" id="" class="form-control" disabled>
                                          <option value="">--None--</option>
                                          <option value="">Version 1</option>
                                          <option value="">Version 2</option>
                                          <option value="">Version 3</option>
                                          <option value="">Version 4</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>


                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group row">
                                    <label for="email" class="col-sm-3 col-md-3 col-form-label">
                                       Problem
                                    </label>
                                    <div class="col-sm-9 col-md-9">
                                       <select name="" id="" class="form-control">
                                          <option value="">--None--</option>
                                          <option value="">No Internet</option>
                                          <option value="">Not printing</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group row">
                                    <label for="email" class="col-sm-3 col-md-3 col-form-label">
                                       Description
                                    </label>
                                    <div class="col-sm-9 col-md-9">
                                       <textarea id="form_message" name="message" class="form-control" placeholder="
                                    Enter case description" rows="4" required="required" data-error="Please, leave us a message."></textarea>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="row" style="margin-top: 20px;">
                              <div id="message">
                                 <b>
                                    **If you have any attachment to add, please create the case first. You will have the opportunity to add the attachments when viewing the case.
                                 </b>
                              </div>
                           </div>
                           <div class="row mt-15">
                              <button class="btn btn-dark" style="width:120px; margin-top: 10px" type="submit">Submit</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>


            </div>
            <!-- /.8 -->

         </div>
         <!-- /.row-->

      </div>
   </div>
@endsection