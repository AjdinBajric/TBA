// const { getCategoryNameByCardId } = require("./helpers");

const NavigatorService = {
    // decodeJWT: function (access_token) {
    //     if(!access_token) throw new Error(); // Should not be reachable, should redirect to sign-in instead
    //
    //     const base64Url = access_token.split('.')[1];
    //     const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    //     const decodedPayload = decodeURIComponent(atob(base64).split('').map(function (c) {
    //         return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    //     }).join(''));
    //
    //     return JSON.parse(decodedPayload);
    // },

    getLandingPage: function () {
        $('#main_container').load("components/landing_page.html", function () {
        });
    }, getCategorySelector: function () {
        $('#main_container').load("components/category_selector.html", function () {
            $('.card:not(.card-coming-soon)').click(function (e) {
                const categoryId = e.target.closest('.card').id;
                NavigatorService.getQuizTypeSelector(categoryId);
            });
        });
    },

    getSignInForm: function () {
        $('#main_container').load("components/sign_in.html", function () {
            $('#signInForm').validate({
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                    error.addClass("w-100 mt-1");
                }, rules: {
                    inputUsername: {
                        required: true, minlength: 3, maxlength: 30
                    }, inputPassword: {
                        required: true, minlength: 8, maxlength: 30
                    },
                }, messages: {
                    inputUsername: {
                        required: "Please enter your username",
                        minlength: "Please enter your username",
                        maxlength: "Your username can't be longer than 30 characters",
                    }, inputPassword: {
                        required: "Please enter a password",
                        minlength: "Your password can't be shorter than 8 characters",
                        maxlength: "Your password can't be longer than 30 characters",
                    },
                }, submitHandler: function (form) {
                    let user = {};
                    user.username = $('#inputUsername').val();
                    user.password = $('#inputPassword').val();
                    $.ajax({
                        url: '../login',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(user),
                        dataType: 'json',
                        success: function (response) {
                            $("#errorMessage").html("");
                            if (response.access_token !== undefined) {
                                localStorage.setItem("access_token", response.access_token);
                                NavigatorService.getCategorySelector();
                            }
                        },
                        error: function (xhr, status, error) {
                            $("#errorMessage").html(xhr.responseJSON.message);
                        }
                    });
                }
            });
        });
    }, getSignUpForm: function () {
        $('#main_container').load("components/sign_up.html", function () {
            $('#signUpForm').validate({
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                    error.addClass("w-100 mt-1");
                }, rules: {
                    inputUsername: {
                        required: true, minlength: 3, maxlength: 30
                    }, inputPassword: {
                        required: true, minlength: 8, maxlength: 30
                    }, inputConfirmPassword: {
                        required: true, equalTo: "#inputPassword"
                    },
                }, messages: {
                    inputUsername: {
                        required: "Please enter a username",
                        minlength: "Your username must be at least 3 characters long",
                        maxlength: "Your username can't be longer than 30 characters",
                    }, inputPassword: {
                        required: "Please enter a password",
                        minlength: "Please enter a password with 8 or characters",
                        maxlength: "Please enter a password shorter than 30 characters"
                    }, inputConfirmPassword: {
                        required: "Please confirm your password", equalTo: "Your passwords don't match"
                    }
                }, submitHandler: function (form) {
                    let user = {};
                    user.username = $('#inputUsername').val();
                    user.password = $('#inputPassword').val();
                    $.ajax({
                        url: '../register',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(user),
                        dataType: 'json',
                        success: function (response) {
                            $("#errorMessage").html("");
                            if (response.success) {
                                NavigatorService.getSignInForm();
                            }
                        },
                        error: function (xhr, status, error) {
                            $("#errorMessage").html("<h5 class='h5 text-danger text-center'>" + xhr.responseJSON.message + "</h5>");
                        }
                    });
                }
            });
        });
    }, handleLogout: function () {
        localStorage.removeItem('access_token');
        NavigatorService.getLandingPage();
    },

    getQuizSelector: function (categoryName, quizTypeId) {
        $.ajax({
            url: '../quiz_by_quiz_type/', method: 'GET', contentType: 'application/json', data: {
                'quiz_type_id': quizTypeId, 'category_name': categoryName,
            }, dataType: 'json', success: function (response) {
                $('#main_container').load("components/quiz_selector.html", function () {
                    const imgSrc = categoryName.split(' ').join('_').toLowerCase();
                    response.quizes.forEach(function (item) {
                        $(".card-container").append(`
                            <div class="card" id="${item.id}">
                              <div class="card-body d-flex">
                                <div class="card-image col-2 d-flex justify-content-center">
                                  <img src="assets/${imgSrc}.png">
                                </div>
                                <div class="card-content">
                                  <div class="card-content-title">
                                    <h3>${item.name}</h3>
                                  </div>
                                  <div class="card-content-subtitle">
                                    <p>${item.description}</p>
                                  </div>
                                </div>
                              </div>
                            </div>
                            `);
                    });

                    $('.card').click(function (e) {
                        const quizId = e.target.closest('.card').id;
                        NavigatorService.getQuiz(quizId);
                    });
                });
            }, error: function (xhr, status, error) {
                console.error(xhr.responseJSON.message);
            }
        });
    }, getQuiz: function (quizId) {
        let points = 0;
        let questions = [];
        let questionIdx = 0;
        $.ajax({
            url: '../quiz_data/' + quizId,
            method: 'GET',
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                $('#main_container').load("components/quiz.html", function () {
                    $(document).ready(function () {
                        // const imgSrc = categoryName.split(' ').join('_').toLowerCase();

                        questions = response.quiz.questions;
                        const firstQuestion = questions[0];
                        const firstQuestionAnswers = firstQuestion.answers;
                        loadNextQuestion(firstQuestion, firstQuestionAnswers);

                        // update quiz title, subtitle and progress
                        $('.cover-heading').text(response.quiz.name);
                        $('.quiz-progress-text').text('Question ' + (questionIdx+1) + '/' + questions.length);


                        // handle answer submit
                        $('#submitAnswerBtn').on('click', function () {
                            const selectedAnswers = $('input[name="answer"]:checked');
                            const selectedAnswersValues = []

                            for (const answer of selectedAnswers) {
                                selectedAnswersValues.push(+(answer.value));
                            }

                            const question = questions[questionIdx];
                            const rightAnswersValues = question.answers.filter(a => a.is_correct).map(a => +(a.id));
                            const rightAnswersText = question.answers.filter(a => a.is_correct).map(a => a.text);

                            // Calculate the points based on the correctness of the selected answers
                            const pointsToBeAwarded = areArraysEqual(selectedAnswersValues, rightAnswersValues) ? +(question.points) : 0;

                            // Add the points for the selected answers to the total points
                            points += pointsToBeAwarded;

                            $('#submitAnswerBtn').css('display', 'none');
                            $('#nextQuestionBtn').css('display', 'block');

                            const correctAnswerEl = $('.correct-answer');
                            correctAnswerEl.css('display', 'block');
                            correctAnswerEl.css('color', pointsToBeAwarded === 0 ? 'red' : 'blue');
                            correctAnswerEl.text(pointsToBeAwarded === 0
                                ? 'Incorrect. The correct answer(s): ' + rightAnswersText.join(', ')
                                : 'Correct!'
                            );
                        });

                        $('#nextQuestionBtn').on('click', function () {
                            // Clear the question text and answers
                            $('.question-text').empty();
                            $('.question-image').css('display', 'none');
                            $('.question-image').attr('src', '');
                            $(".answers-container").empty();
                            $('#submitAnswerBtn').css('display', 'block');
                            $('#nextQuestionBtn').css('display', 'none');
                            $('.correct-answer').css('display', 'none');

                            questionIdx += 1;

                            const progressWidth = (questionIdx / questions.length) * 100;
                            $('.progress-bar').css('width', progressWidth + '%');
                            $('.quiz-progress-text').text('Question ' + (questionIdx+1) + '/' + questions.length);

                            if (questionIdx < questions.length) {
                                const nextQuestion = questions[questionIdx];
                                loadNextQuestion(nextQuestion);
                                return;
                            }

                            $('.card-container.quiz').css('display', 'none');
                            $('.quiz-info').css('display', 'none');
                            $('.card-container.score').css('display', 'flex');

                            // Show the total points
                            const scoredPercentage = +((points / response.quiz.max_points * 100).toFixed(2));
                            const hasPassed = scoredPercentage >= response.quiz.percentage_to_pass;
                            $('.has-passed-text').text(hasPassed ? 'Passed' : 'Failed');
                            $('.has-passed-text').css('color', hasPassed ? 'blue' : 'red');
                            $('.points-scored-value').text(points + ' / ' + response.quiz.max_points);
                            $('.right-answers-value').text(points / questions[0].points + ' / ' +
                                response.quiz.max_points / questions[0].points);
                            $('.percentage-scored-value').text(scoredPercentage + '%');
                        });
                    });
                });
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseJSON.message);
            }
        });

        const loadNextQuestion = function (question) {
            // Load in the question text
            $('.question-text').text(question.text);

            if(question.image_url) {
                $('.question-image').css('display', 'block');
                $('.question-image').attr('src', question.image_url);
            }

            const answerType = question.text.toLowerCase().includes('više tačnih odgovora') ? 'checkbox' : 'radio';
            // Load in the answers
            question.answers.forEach(function (item) {
                $(".answers-container").append(`
                    <div class="answer">
                      <input class="mx-2" id="${item.id}" type="${answerType}" name="answer" value="${item.id}">
                      <label style="cursor: pointer" for="${item.id}">${item.text}</label>
                    </div>
                `);
            });
        }

        const areArraysEqual = function (array1, array2) {
            // Check if both arrays have the same length
            if (array1.length !== array2.length) {
                return false;
            }

            // Sort the arrays to ensure consistent order of elements
            const sortedArray1 = array1.slice().sort();
            const sortedArray2 = array2.slice().sort();

            // Compare each element of the sorted arrays
            for (let i = 0; i < sortedArray1.length; i++) {
                if (sortedArray1[i] !== sortedArray2[i]) {
                    return false;
                }
            }

            return true;
        }
    },
    getQuizTypeSelector: function (categoryId) {
        const categoryName = NavigatorService.getCategoryNameByCardId(categoryId);
        $.ajax({
            url: '../quiz_type_by_category/', method: 'GET', contentType: 'application/json', data: {
                'category': categoryName,
            }, dataType: 'json', success: function (response) {
                $('#main_container').load("components/quiz_type_selector.html", function () {
                    const imgSrc = categoryName.split(' ').join('_').toLowerCase();
                    response.types.forEach(function (item) {
                        $(".card-container").append(`
                            <div class="card" id="${item.id}">
                              <div class="card-body d-flex">
                                <div class="card-image col-2 d-flex justify-content-center">
                                  <img src="assets/${imgSrc}.png">
                                </div>
                                <div class="card-content">
                                  <div class="card-content-title">
                                    <h3>${item.type}</h3>
                                  </div>
                                </div>
                              </div>
                            </div>
                            `);
                    });

                    $('.card').click(function (e) {
                        const quizTypeId = e.target.closest('.card').id;
                        NavigatorService.getQuizSelector(categoryName, quizTypeId);
                    });
                });
            }, error: function (xhr, status, error) {
                console.error(xhr.responseJSON.message);
            }
        });
    }, getCategoryNameByCardId: function (cardId) {
        const lookup_table = {
            1: 'A Kategorija', 2: 'B Kategorija', 3: 'C Kategorija', 4: 'D Kategorija', 5: 'Prva pomoc',
        }

        return lookup_table[cardId];
    }
};