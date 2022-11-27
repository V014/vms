<?php

include_once "./includes/entity/topic.php";
include_once "./includes/entity/message.php";
include_once "./includes/entity/user.php";
include_once "./includes/auth.php";

$topics = Topic::all();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = Auth::getUser();

    $topic = [
        "title" => $_POST["title"],
        "user_id" => $user->id,
        "date_created" => date("Y/m/d", strtotime("today")),
    ];

    Topic::create($topic);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VMS - Forum</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
</head>

<body>
    <div id="wrapper">
        <?php include_once('./components/nav_bar.php') ?>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                        <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search for ..."><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                        </form>
                        <?php navActions(); ?>
                    </div>
                </nav>
                <div class="container-fluid">
                    <h3 class="text-dark mb-4">Forum</h3>
                    <p>Join, View and Create Helpful Forum Discussions</p>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <p>Create Forum Topic</p>
                                    <form class="user" name="forum_topic" method="POST" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>">
                                        <div class="mb-3">
                                            <input class="form-control form-control-user" type="text" id="exampleFirstName" placeholder="Topic Title" name="title" required>
                                        </div>
                                        <button class="btn btn-primary d-block btn-user w-100" type="submit">Submit Topic</button>
                                        <hr>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold">Forum Topics and Discussions</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 text-nowrap">
                                    <span><?php echo count($topics); ?> Topics</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 align-self-center">
                                    <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite">Showing 1 to 10 of Forum Posts</p>
                                </div>
                                <div class="col-md-6">
                                    <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                        <ul class="pagination">
                                            <li class="page-item disabled"><a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a></li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <?php foreach ($topics as $topic) { ?>
                                        <?php
                                        $messages = Message::all($topic->id);
                                        $messageCount = count($messages);
                                        $lastMessage = end($messages);
                                        $poster = false;

                                        if ($lastMessage) {
                                            $poster = User::find($lastMessage->userID);
                                        }

                                        $user = User::find($topic->userID);
                                        ?>
                                        <div class="row">
                                            <div class="container-fluid mt-100">
                                                <div class="card mb-3">
                                                    <div class="card-header pl-0 pr-0">
                                                        <div class="row no-gutters w-100 align-items-center">
                                                            <div class="col ml-3"></div>
                                                            <div class="col-4 text-muted">
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col-4">Replies</div>
                                                                    <div class="col-8">Last update</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body py-3">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col"> <a href="forum_topic_messages.php?id=<?php echo $topic->id; ?>" class="text-big" data-abc="true"><?php echo $topic->title; ?></a>
                                                                <div class="text-muted small mt-1">Created <?php echo $topic->dateCreated; ?> &nbsp;·&nbsp; <span href="javascript:void(0)" class="text-muted" data-abc="true"><?php echo ucfirst($user->username); ?></span></div>
                                                            </div>
                                                            <div class="d-none d-md-block col-4">
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col-4"><?php echo $messageCount; ?></div>
                                                                    <div class="media col-8 align-items-center">
                                                                        <?php if ($poster) { ?>
                                                                            <img src="<?php echo $poster->profilePicture; ?>" alt="" class="d-block ui-w-30 rounded-circle" style="width: 65px;">
                                                                            <div class="media-body flex-truncate ml-2">
                                                                                <div class="line-height-1 text-truncate"><?php echo $lastMessage->dateCreated; ?></div> <a href="javascript:void(0)" class="text-muted small text-truncate" data-abc="true">by <?php echo ucfirst($poster->username); ?></a>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <p>No Poster</p>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                            <?php } ?>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <footer class="bg-white sticky-footer">
                        <div class="container my-auto">
                            <div class="text-center my-auto copyright"><span>Copyright © Brand 2022</span></div>
                        </div>
                    </footer>
                </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
            </div>
            <script src="assets/bootstrap/js/bootstrap.min.js"></script>
            <script src="assets/js/bs-init.js"></script>
            <script src="assets/js/theme.js"></script>
</body>

</html>
