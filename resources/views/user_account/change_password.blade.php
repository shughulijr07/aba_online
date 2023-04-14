@extends($extended_layout)

@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="pe-7s-lock icon-gradient bg-malibu-beach"> </i>
                </div>
                <div>
                    <div class="text-primary">Change Password</div>
                    <div class="page-title-subheading">
                        To change your password enter your old password, then enter your new password,<br>
                        confirm your new password then place "Change Password" button to change your password
                    </div>
                </div>
            </div>

            <!--actions' menu starts here -->
            @include('layouts.administrator.page-title-actions')
            <!--actions' menu ends here -->
            
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Password Changing Form</h5>
                    <form action="/update_password" method="POST">
                        @include('user_account.change_password_form')
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection