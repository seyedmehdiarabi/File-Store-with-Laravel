<section>
    <div class="container">
        <div class="row">
            <div id="success-div" class="col-sm-12 d-none">
                <div class="alert alert-div fade alert-simple alert-success alert-dismissible font__family-montserrat font__size-16 font__weight-light brk-library-rendered rendered show">
                    <div class="close font__size-18" onclick="close_alert('success')">
                                    <span>
                                        <i class="fa fa-times greencross"></i>
									</span>
                    </div>
                    <i class="start-icon fa fa-check-circle faa-tada animated"></i>
                    <strong id="alert-success" class="font__weight-semibold"></strong>
                    <div class="progress" dir="ltr">
                        <div id="dynamic" class="progress-bar bg-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span id="current-progress"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="info-div" class="col-sm-12 d-none">
                <div class="alert fade alert-simple alert-info alert-dismissible font__family-montserrat font__size-16 font__weight-light brk-library-rendered rendered show" role="alert" data-brk-library="component__alert">
                    <div class="close font__size-18" onclick="close_alert('info')">
									<span>
										<i class="fa fa-times blue-cross"></i>
									</span>
                    </div>
                    <i class="start-icon  fa fa-info-circle faa-shake animated"></i>
                    <strong id="alert-info" class="font__weight-semibold"></strong>
                    <div class="progress" dir="ltr">
                        <div id="dynamic" class="progress-bar bg-info progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span id="current-progress"></span>
                        </div>
                    </div>
                </div>

            </div>
            <div id="warning-div" class="col-sm-12 d-none">
                <div class="alert fade alert-simple alert-warning alert-dismissible font__family-montserrat font__size-16 font__weight-light brk-library-rendered rendered show" role="alert" data-brk-library="component__alert">
                    <div class="close font__size-18" onclick="close_alert('warning')">
									<span>
										<i class="fa fa-times warning"></i>
									</span>
                    </div>
                    <i class="start-icon fa fa-exclamation-triangle faa-flash animated"></i>
                    <strong id="alert-warning" class="font__weight-semibold"></strong>
                    <div class="progress" dir="ltr">
                        <div id="dynamic" class="progress-bar bg-warning progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span id="current-progress"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="danger-div" class="col-sm-12 d-none">
                <div class="alert fade alert-simple alert-danger alert-dismissible font__family-montserrat font__size-16 font__weight-light brk-library-rendered rendered show" role="alert" data-brk-library="component__alert">
                    <div class="close font__size-18" onclick="close_alert('danger')">
									<span>
										<i class="fa fa-times danger"></i>
									</span>
                    </div>
                    <i class="start-icon fa fa-times-circle faa-pulse animated"></i>
                    <strong id="alert-danger" class="font__weight-semibold"></strong>
                    <div class="progress" dir="ltr">
                        <div id="dynamic" class="progress-bar bg-danger progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span id="current-progress"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="primary-div" class="col-sm-12 d-none">
                <div class="alert fade alert-simple alert-primary alert-dismissible font__family-montserrat font__size-16 font__weight-light brk-library-rendered rendered show" role="alert" data-brk-library="component__alert">
                    <div class="close font__size-18" onclick="close_alert('primary')">
                        <span><i class="fa fa-times alertprimary"></i></span>
                    </div>
                    <i class="start-icon fa fa-thumbs-up faa-bounce animated"></i>
                    <strong id="alert-primary" class="font__weight-semibold"></strong>
                    <div class="progress" dir="ltr">
                        <div id="dynamic" class="progress-bar bg-primary progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span id="current-progress"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
