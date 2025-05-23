<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Opportunities - myResto Today</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // The image preview functionality is now handled by the image-cropper.js file
    </script>
    <!-- Include Cropper.js CSS -->
    <link rel="stylesheet" href="assets/css/cropper.min.css">
    <link rel="stylesheet" href="assets/css/image-cropper.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: rgb(10, 5, 1);
            color: #fff;
            line-height: 1.6;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }
        .header {
            background-color:rgb(10, 5, 1);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header-text h1 {
            color:rgb(243, 246, 248);
            font-size: 1.8rem;
            margin-bottom: 0;
        }
        .header-text p {
            color:rgb(240, 246, 247);
            margin-bottom: 0;
        }
        .main-container {
            background-color: rgb(20, 12, 5);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            padding: 30px;
            margin-top: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .form-label {
            font-weight: 500;
            color: #fff;
        }
        .form-control {
            border-radius: 4px;
            border: 1px solid rgba(255,255,255,0.2);
            padding: 10px 15px;
            background-color: rgb(30, 20, 10);
            color: #fff;
        }
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            background-color: rgb(40, 25, 15);
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .btn-success {
            background-color: #2ecc71;
            border-color: #2ecc71;
        }
        .btn-success:hover {
            background-color: #27ae60;
            border-color: #27ae60;
        }
        .skill-badge {
            display: inline-block;
            padding: 5px 12px;
            margin: 3px;
            color: #fff;
            background-color: #3498db;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .social-links a {
            color: white;
            font-size: 1.2rem;
            margin: 0 10px;
            transition: color 0.3s ease;
        }
        .social-links a:hover {
            color: #3498db;
        }
        .carousel-item {
            height: 400px;
            position: relative;
        }
        .carousel-item img {
            object-fit: cover;
            height: 100%;
        }
        .carousel-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }
        .carousel-caption {
            z-index: 2;
            bottom: 50%;
            transform: translateY(50%);
        }
        .carousel-caption h3 {
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .carousel-caption p {
            font-size: 1.2rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }
        .job-description {
            background-color: rgb(25, 15, 8);
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            color: #fff;
        }
        .job-description h3 {
            color: #fff;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .disclaimer {
            background-color: rgb(30, 20, 10);
            border-left: 4px solid #f39c12;
            padding: 15px;
            margin: 20px 0;
            font-size: 0.9rem;
            color: #fff;
        }
    </style>
</head>
<body>

<!-- Header Section -->
<header class="header">
    <div class="container header-content">
        <img src="https://myresto.today/03_images/logo.png" alt="myResto Today Logo" style="width: 180px; height: auto; margin-right: 45px;">
        <div class="header-text">
            <h1>Join Our Team</h1>
            <p>myResto Today - Building the future of food technology</p>
        </div>
    </div>
</header>

<!-- Image Slider -->
<div id="careerSlider" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="career1.jpg" class="d-block w-100" alt="Career Growth">
            <div class="carousel-caption">
                <h3>Grow With Us</h3>
                <p>Develop your skills in a supportive environment</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="career2.jpg" class="d-block w-100" alt="Team Work">
            <div class="carousel-caption">
                <h3>Collaborative Culture</h3>
                <p>Work with talented professionals who share your passion</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="career3.jpg" class="d-block w-100" alt="Opportunities">
            <div class="carousel-caption">
                <h3>Endless Opportunities</h3>
                <p>Be part of the AI-driven revolution in the food industry</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#careerSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#careerSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
</div>

<div class="container">
    <!-- Brief Details Section -->
    <div class="text-center mt-5 mb-4">
        <h2>Join Our Growing Team</h2>
        <p class="lead">We are looking for passionate and skilled professionals to join our innovative foodtech startup. If you have the expertise and the drive to make a difference, we want to hear from you!</p>
    </div>

    <!-- Career Application Form -->
    <div class="main-container">
        <h2 class="text-center mb-4">Career Application Form</h2>
        <form action="submit_application.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="profile_image" class="form-label">Upload Profile Image</label>
                    <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*" required>
                    <div id="imagePreview" class="mt-2" style="display: none;">
                        <img id="preview" src="#" alt="Profile Preview" class="profile-preview">
                    </div>
                    <input type="hidden" id="croppedImage" name="croppedImage">
                    <small class="form-text text-light">Image will be cropped to a square format</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="district" class="form-label">District</label>
                    <select id="district" name="district" class="form-select" required>
                        <option value="Kerala > Thiruvananthapuram">Kerala > Thiruvananthapuram</option> 
                        <option value="Kerala > Kollam">Kerala > Kollam</option> 
                        <option value="Kerala > Pathanamthitta">Kerala > Pathanamthitta</option> 
                        <option value="Kerala > Alappuzha">Kerala > Alappuzha</option> 
                        <option value="Kerala > Kottayam">Kerala > Kottayam</option> 
                        <option value="Kerala > Idukki">Kerala > Idukki</option> 
                        <option value="Kerala > Ernakulam">Kerala > Ernakulam</option> 
                        <option value="Kerala > Thrissur">Kerala > Thrissur</option> 
                        <option value="Kerala > Palakkad">Kerala > Palakkad</option> 
                        <option value="Kerala > Malappuram">Kerala > Malappuram</option> 
                        <option value="Kerala > Kozhikode">Kerala > Kozhikode</option> 
                        <option value="Kerala > Wayanad">Kerala > Wayanad</option> 
                        <option value="Kerala > Kannur">Kerala > Kannur</option> 
                        <option value="Kerala > Kasaragod">Kerala > Kasaragod</option> 
                        <option value="Karnataka > Bagalkot">Karnataka > Bagalkot</option> 
                        <option value="Karnataka > Ballari">Karnataka > Ballari</option> 
                        <option value="Karnataka > Belagavi">Karnataka > Belagavi</option> 
                        <option value="Karnataka > Bengaluru Rural">Karnataka > Bengaluru Rural</option> 
                        <option value="Karnataka > Bengaluru Urban">Karnataka > Bengaluru Urban</option> 
                        <option value="Karnataka > Bidar">Karnataka > Bidar</option> 
                        <option value="Karnataka > Chamarajanagar">Karnataka > Chamarajanagar</option> 
                        <option value="Karnataka > Chikballapur">Karnataka > Chikballapur</option> 
                        <option value="Karnataka > Chikkamagaluru">Karnataka > Chikkamagaluru</option> 
                        <option value="Karnataka > Chitradurga">Karnataka > Chitradurga</option> 
                        <option value="Karnataka > Dakshina Kannada">Karnataka > Dakshina Kannada</option> 
                        <option value="Karnataka > Davanagere">Karnataka > Davanagere</option> 
                        <option value="Karnataka > Dharwad">Karnataka > Dharwad</option> 
                        <option value="Karnataka > Gadag">Karnataka > Gadag</option> 
                        <option value="Karnataka > Hassan">Karnataka > Hassan</option> 
                        <option value="Karnataka > Haveri">Karnataka > Haveri</option> 
                        <option value="Karnataka > Kalaburagi">Karnataka > Kalaburagi</option> 
                        <option value="Karnataka > Kodagu">Karnataka > Kodagu</option> 
                        <option value="Karnataka > Kolar">Karnataka > Kolar</option> 
                        <option value="Karnataka > Koppal">Karnataka > Koppal</option> 
                        <option value="Karnataka > Mandya">Karnataka > Mandya</option> 
                        <option value="Karnataka > Mysuru">Karnataka > Mysuru</option> 
                        <option value="Karnataka > Raichur">Karnataka > Raichur</option> 
                        <option value="Karnataka > Ramanagara">Karnataka > Ramanagara</option> 
                        <option value="Karnataka > Shivamogga">Karnataka > Shivamogga</option> 
                        <option value="Karnataka > Tumakuru">Karnataka > Tumakuru</option> 
                        <option value="Karnataka > Udupi">Karnataka > Udupi</option> 
                        <option value="Karnataka > Uttara Kannada">Karnataka > Uttara Kannada</option> 
                        <option value="Karnataka > Vijayapura">Karnataka > Vijayapura</option> 
                        <option value="Karnataka > Yadgir">Karnataka > Yadgir</option> 
                        <option value="Tamil Nadu > Ariyalur">Tamil Nadu > Ariyalur</option> 
                        <option value="Tamil Nadu > Chengalpattu">Tamil Nadu > Chengalpattu</option> 
                        <option value="Tamil Nadu > Chennai">Tamil Nadu > Chennai</option> 
                        <option value="Tamil Nadu > Coimbatore">Tamil Nadu > Coimbatore</option> 
                        <option value="Tamil Nadu > Cuddalore">Tamil Nadu > Cuddalore</option> 
                        <option value="Tamil Nadu > Dharmapuri">Tamil Nadu > Dharmapuri</option> 
                        <option value="Tamil Nadu > Dindigul">Tamil Nadu > Dindigul</option> 
                        <option value="Tamil Nadu > Erode">Tamil Nadu > Erode</option> 
                        <option value="Tamil Nadu > Kallakurichi">Tamil Nadu > Kallakurichi</option> 
                        <option value="Tamil Nadu > Kancheepuram">Tamil Nadu > Kancheepuram</option> 
                        <option value="Tamil Nadu > Karur">Tamil Nadu > Karur</option> 
                        <option value="Tamil Nadu > Krishnagiri">Tamil Nadu > Krishnagiri</option> 
                        <option value="Tamil Nadu > Madurai">Tamil Nadu > Madurai</option> 
                        <option value="Tamil Nadu > Mayiladuthurai">Tamil Nadu > Mayiladuthurai</option> 
                        <option value="Tamil Nadu > Nagapattinam">Tamil Nadu > Nagapattinam</option> 
                        <option value="Tamil Nadu > Namakkal">Tamil Nadu > Namakkal</option> 
                        <option value="Tamil Nadu > Nilgiris">Tamil Nadu > Nilgiris</option> 
                        <option value="Tamil Nadu > Perambalur">Tamil Nadu > Perambalur</option> 
                        <option value="Tamil Nadu > Pudukkottai">Tamil Nadu > Pudukkottai</option> 
                        <option value="Tamil Nadu > Ramanathapuram">Tamil Nadu > Ramanathapuram</option> 
                        <option value="Tamil Nadu > Ranipet">Tamil Nadu > Ranipet</option> 
                        <option value="Tamil Nadu > Salem">Tamil Nadu > Salem</option> 
                        <option value="Tamil Nadu > Sivaganga">Tamil Nadu > Sivaganga</option> 
                        <option value="Tamil Nadu > Tenkasi">Tamil Nadu > Tenkasi</option> 
                        <option value="Tamil Nadu > Thanjavur">Tamil Nadu > Thanjavur</option> 
                        <option value="Tamil Nadu > Theni">Tamil Nadu > Theni</option> 
                        <option value="Tamil Nadu > Thoothukudi">Tamil Nadu > Thoothukudi</option> 
                        <option value="Tamil Nadu > Tiruchirappalli">Tamil Nadu > Tiruchirappalli</option> 
                        <option value="Tamil Nadu > Tirunelveli">Tamil Nadu > Tirunelveli</option> 
                        <option value="Tamil Nadu > Tirupattur">Tamil Nadu > Tirupattur</option> 
                        <option value="Tamil Nadu > Tiruppur">Tamil Nadu > Tiruppur</option> 
                        <option value="Tamil Nadu > Tiruvallur">Tamil Nadu > Tiruvallur</option> 
                        <option value="Tamil Nadu > Tiruvannamalai">Tamil Nadu > Tiruvannamalai</option> 
                        <option value="Tamil Nadu > Tiruvarur">Tamil Nadu > Tiruvarur</option> 
                        <option value="Tamil Nadu > Vellore">Tamil Nadu > Vellore</option> 
                        <option value="Tamil Nadu > Viluppuram">Tamil Nadu > Viluppuram</option> 
                        <option value="Tamil Nadu > Virudhunagar">Tamil Nadu > Virudhunagar</option> 
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="native_location" class="form-label">Local Area & Address</label>
                    <input type="text" id="native_location" name="native_location" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="skills" class="form-label">Skills</label>
                <div class="input-group">
                    <input type="text" id="skills_input" class="form-control" placeholder="Enter a skill" required>
                    <button type="button" class="btn btn-primary" onclick="addSkill()">Add Skill</button>
                </div>
                <div id="skills_container" class="mt-3"></div>
            </div>

            <div class="mb-3">
                <label for="experience" class="form-label">Experience</label>
                <div id="experience_container">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" name="experience_field[]" class="form-control" placeholder="Field (e.g., PHP Developer)" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="experience_company[]" class="form-control" placeholder="Company Name" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="experience_duration[]" class="form-control" placeholder="Duration (e.g., 3 years)" required>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-primary" onclick="addExperience()"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="linkedin" class="form-label">LinkedIn Profile</label>
                    <input type="url" id="linkedin" name="linkedin" class="form-control" required>
                    <div id="linkedin_error" class="text-danger mt-1" style="display: none;">Please provide a valid LinkedIn URL (e.g., linkedin.com, www.linkedin.com, https://linkedin.com, etc.).</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="mobile" class="form-label">Mobile Number</label>
                    <div class="input-group">
                        <span class="input-group-text">+91 India</span>
                        <input type="tel" id="mobile" name="mobile" class="form-control" pattern="[0-9]{10}" maxlength="10" title="Please enter exactly 10 digits" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cv" class="form-label">Upload CV</label>
                    <input type="file" id="cv" name="cv" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                </div>
            </div>

            <div class="disclaimer">
                <h5><i class="fas fa-exclamation-circle me-2"></i>Important Notice</h5>
                <p>By submitting your application, you acknowledge and agree to the following:</p>
                <ul>
                    <li>All information provided is accurate and complete.</li>
                    <li>This position initially offers time equity only (no monthly salary).</li>
                    <li>The first 2 months will be spent on building clones to assess your skills.</li>
                    <li>If promoted to real projects, you will be required to work with the company for the next year.</li>
                    <li>You will bear all expenses incurred during the promotion process.</li>
                    <li>Remote work is not supported; the office is located in Aluva.</li>
                </ul>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2 mt-3" onclick="return validateLinkedIn()">Submit Application</button>
        </form>
    </div>

    <!-- Job Description Section -->
    <div class="job-description">
        <h2 class="text-center mb-4">Job Description</h2>
        <div class="row">
            <div class="col-md-12">
                <h3>MERN Stack Developer with GoLang</h3>
                <p>Be a part of the AI-driven revolution in the food industry! We are looking for an experienced full stack MERN & golang developer with strong experience in Next.js, Node.js, MySQL, and git.</p>
                
                <div class="mb-4">
                    <h4><i class="fas fa-briefcase me-2"></i>Current Openings</h4>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i>Tech Team (4 positions)</li>
                        <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i>MERN Stack (Next.js & node.js) with GoLang Microservices</li>
                        <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i>Equity-based opportunity - Starting with time equity only</li>
                        <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i>Opportunity for both men and women</li>
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h4><i class="fas fa-tasks me-2"></i>Key Responsibilities</h4>
                    <ul>
                        <li>Develop scalable web applications using Next.js (react with SSR/ISR) with Google Firebase and APIs</li>
                        <li>Build and maintain robust backend services and REST APIs using Node.js</li>
                        <li>Design and manage databases using MySQL and ORM tools (e.g. Serialize)</li>
                        <li>Collaborate with product, design, and QA teams to deliver seamless user experiences</li>
                        <li>Optimize application performance, security, and data structure</li>
                        <li>Implement role-based dashboards (admin, restaurant managers, etc.)</li>
                        <li>Write clean, maintainable, and well-documented code</li>
                        <li>Troubleshoot, test, and maintain core product software and databases</li>
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h4><i class="fas fa-laptop-code me-2"></i>Required Skills</h4>
                    <ul>
                        <li>Proficiency in JavaScript/ES6+ and TypeScript (preferred)</li>
                        <li>Strong experience in Next.js, React.js, SSR</li>
                        <li>Strong backend development skills using Node.js and Express.js</li>
                        <li>Strong understanding of MySQL, database schema design, joins, and indexing</li>
                        <li>Experience with RESTful APIs and third-party integrations (e.g. payment gateways, POS)</li>
                        <li>Experience with Git, CI/CD, and Agile workflows</li>
                        <li>Willingness to learn GoLang during the probationary period</li>
                    </ul>
                </div>
                
                <div>
                    <h4><i class="fas fa-info-circle me-2"></i>Additional Information</h4>
                    <p>The employee will have to work in an office; remote work is not supported. The office is located in Aluva. The tech stack includes MongoDB and PostgreSQL.</p>
                    <p>Applications are invited from candidates who are interested in experiencing new things, learning more, and gaining more skills.</p>
                    <p>The first 2 months will be spent on building clones. If the quality is maintained, candidates will be promoted to real projects after 2 months if they are willing to work with the company for the remaining year. The promoted employee will be required to work with the company for the next year.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer Section -->
<footer class="footer mt-5">
    <div class="container">
        <div class="social-links mb-3">
            <a href="https://www.facebook.com/myrestotoday" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="https://www.instagram.com/myrestotoday" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="https://www.linkedin.com/company/myrestotoday" target="_blank"><i class="fab fa-linkedin-in"></i></a>
            <a href="https://twitter.com/myrestotoday" target="_blank"><i class="fab fa-twitter"></i></a>
        </div>
        <p>&copy; 2025 myResto Today. All rights reserved.</p>
    </div>
</footer>

<script>
    function addSkill() {
        const skillsInput = document.getElementById('skills_input');
        const skillsContainer = document.getElementById('skills_container');
        const skill = skillsInput.value.trim();

        if (skill) {
            const skillBadge = document.createElement('span');
            skillBadge.className = 'skill-badge';
            skillBadge.innerText = skill;

            skillsContainer.appendChild(skillBadge);

            const skillInput = document.createElement('input');
            skillInput.type = 'hidden';
            skillInput.name = 'skills[]';
            skillInput.value = skill;
            skillsContainer.appendChild(skillInput);

            skillsInput.value = ''; 
        }
    }

    function addExperience() {
        const container = document.getElementById('experience_container');
        const row = document.createElement('div');
        row.className = 'row mb-3';
        row.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="experience_field[]" class="form-control" placeholder="Field (e.g., PHP Developer)" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="experience_company[]" class="form-control" placeholder="Company Name" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="experience_duration[]" class="form-control" placeholder="Duration (e.g., 3 years)" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-trash"></i></button>
            </div>`;
        container.appendChild(row);
    }

    function validateLinkedIn() {
        const linkedinInput = document.getElementById('linkedin');
        const linkedinValue = linkedinInput.value.trim();
        const validFormats = [
            'https://www.linkedin.com/',
            'https://linkedin.com/',
            'https://in.linkedin.com/',
            'http://www.linkedin.com/',
            'http://linkedin.com/',
            'http://in.linkedin.com/',
            'www.linkedin.com',
            'linkedin.com',
            'in.linkedin.com'
        ];
        
        const isValid = validFormats.some(format => linkedinValue.startsWith(format));
        
        if (!isValid) {
            document.getElementById('linkedin_error').style.display = 'block';
            return false;
        }
        document.getElementById('linkedin_error').style.display = 'none';
        return true;
    }
</script>

<!-- Image Cropping Modal -->
<div id="cropModal" class="crop-modal">
    <div class="crop-modal-content">
        <div class="crop-container">
            <img id="cropperImage" src="#" alt="Image to crop">
        </div>
        <div class="crop-actions">
            <button type="button" id="cancelCrop" class="btn btn-secondary">Cancel</button>
            <button type="button" id="saveCrop" class="btn btn-primary">Crop & Save</button>
        </div>
    </div>
</div>

<!-- Include Cropper.js and custom image cropper script -->
<script src="assets/js/cropper.min.js"></script>
<script src="assets/js/image-cropper.js"></script>
</body>
</html>
