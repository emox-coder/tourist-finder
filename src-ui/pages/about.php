<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../includes/head.php'; ?>
    <title>Tourist Attraction Finder - About Us</title>
    <link rel="stylesheet" href="../assets/css/about.css">
</head>
<body>
    <div class="content-1">
        <?php include '../includes/header.php'; ?>

        <div class="our-story">
            <div class="our-story-text">
                <h1>Our Story</h1>
                <h3>From Lost Tourist to Travel Innovators, </h3>
                <p style="margin-bottom:20px;">At Tourist Attraction Finder, our journey began<br>
                    with a shared passion for exploration beyond the<br>
                    typical tourist path.
                </p>
                <p>We saw a need for a resource that focused on<br>
                    authenticity, local connections, and hidden gems.
                </p>
            </div>
            <div class="logo-ver2">
                <img src="../assets/img/logo-ver2.png">
            </div>
        </div>

        <div class="our-mission">
            <div class="our-mission-background">
                <div class="our-mission-group">
                    <img src="../assets/img/our-mission-group.png">
                </div>
            </div>
        </div>

        <div class="meet-the-team">
            <div>
                <h1 class="meet">Meet the Team</h1>
            </div>
            <div class="meet-the-team-container">
                <div class="meet-the-team-img">
                    <div>
                        <img src="../assets/img/paladin.png">
                        <h2>Founder and CEO</h2>
                    </div>
                    <div>
                        <img src="../assets/img/refoyo.png">
                        <h2>UI/UX Designer</h2>
                    </div>
                    <div>
                        <img src="../assets/img/david.png">
                        <h2>Front-End/Back-End Dev</h2>
                    </div>
                </div>
            </div>

            <div class="meet-the-team-container">
                <div class="meet-the-team-img2">
                    <div>
                        <img src="../assets/img/candes.png">
                        <h2>Back - End Developer</h2>
                    </div>
                    <div>
                        <img src="../assets/img/balaoro.png">
                        <h2>Database Developer</h2>
                    </div>
                </div>
            </div>

            <div class="contact-us-container">
                <div class="container1">
                    <div class="div1">
                        <span class="material-icons">email</span>
                        <h1>Contact Us</h1>
                    </div>

                    <div class="div2">
                        <p>Lets Map Out Your Next Conversation</p>
                    </div>

                    <div class="div3">
                        <span class="material-icons">email</span>
                        <h3>Email <br> <a>hello@taffinder.com</a></h3>
                    </div>

                    <div class="div4">
                        <span class="material-icons">support_agent</span>
                        <h3>Support <br><a>help@taffinder.com</a></h3>

                    </div>

                    <div class="div5">
                        <span class="material-icons">phone</span>
                        <h3>Phone <br><a>+1 (1713) 376-4383</a></h3>
                    </div>

                    <div class="div6">
                        <span class="material-icons">location_on</span>
                        <h3>Basecamp <br> <a>basecamp finder.com</a></h3>
                    </div>
                </div>


                <div class="container2">
                    <div class="icons-container">
                        <div class="instagram">
                            <img src="../assets/img/instagram.png">
                            <h3 style="color: #EFA654;">Instagram</h3>
                        </div>

                        <div class="tiktok">
                            <span class="material-icons">tiktok</span>
                            <h3 style="color: #EFA654;">TikTok</h3>
                        </div>

                        <div class="twitter">
                            <img src="../assets/img/twitter.png">
                            <h3 style="color: #EFA654;">Twitter</h3>
                        </div>
                    </div>

                    <div class="input-container">
                        <div class="namediv">
                            <input type="text" id="name" name="name" placeholder="Name:" required>
                        </div>

                        <div class="emaildiv">
                            <input type="email" id="email" name="email" placeholder="Email:" required>
                        </div>

                        <div class="subjectdiv">
                            <label for="subject">Subject: <br> Choose an option</label>
                            <select id="subject" name="subject">
                                <option value="#">#</option>
                                <option value="#">#</option>
                                <option value="#">#s</option>
                            </select>
                        </div>

                        <div class="messagediv">
                            <textarea id="message" name="message" placeholder="Message:" required></textarea>
                        </div>

                        <div class="submitdiv">
                            <button class="submit-btn" type="submit" id="submitBtn">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <footer style="padding:100px;"></footer>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>