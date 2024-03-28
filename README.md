# Townsend Music Laravel Coding Test

## MY notes

- I wouldn't count this as a completely finished task due to time constraints
  are somewhat important including this brief rise up the total time was two hours and 10 minutes
- The models and their relationships have already been established.
    - These were incorrect in places
- The api returns incorrect data has missing incorrect image URL And there was a missing sort Functionality
- I created some simple tests to ensure that the output remains somewhat identical however I did not test all the
  parameters and the sort functionality was missing on the existing api
- I did an additional route rather than juggling nullable
- The section model is automatically loaded and can be found in \App\Providers\AppServiceProvider::boot This is not an
  optimal place to do it
- I found beat style guide incomplete in the future it would be more beneficial to provide a linting file Town
  constraints would have reduced my ability to deliver so I just used the defaults linting that I had to hand

My general approach to projects like this which was accomplished a few times is to

- create extensive tests of old apis usually feature tests
    - I would use logging on production servers especially if it is a very old API to identify any specific problems for
      example the sort not working on the existing api could cause an issue if fixed as it would update the fingerprint
      of anybody who has implemented the api incorrectly
- I would then break apart the functionality into smaller usable chunks for example the pagination and ordering methods 
- Validate my input based upon the logs and testing 
- Move any shared functionality into appropriate places  Such as on models
- Move any queries onto the eloquence system and use query builder to rebuld up And shorten queries   



## Objective

Your task is to refactor the `GetProductsController` without altering its existing functionality. This controller is
used by an API route that provides a list of products to external services. It is vitally important that nothing is
changed in the data that it returns.

The existing route, `/api/products`, returns all products and this functionally should remain unchanged.

We would like you to add a new route alongside it, `/api/products/{section}`, which will return all products belonging
to the specified section.

## What we're looking for

We are looking to see your understanding and use of the Laravel ecosystem as well as adopting the latest PHP
fundamentals and best practices.
We want the code to be clearly readable, maintainable, type-safe and flexible to grow with future feature requirements.

Things to bear in mind:

- The API will be used by external actors so the state of the query parameters cannot be verified.
- How can we ensure that the functionality has not changed during the refactor?
- Don't do everything yourself, if there's a feature in Laravel or 3rd party package that can do what you want for you,
  use it.

## Code Style

As a point of reference within the task we would like you to adopt
the [Spatie Laravel PHP style guide](https://spatie.be/guidelines/laravel-php) which highlights the standard of code we
are looking for.

## Models and Relationships

The models and their relationships have already been established.

## Development Environment

Set up your dev environment using the setup that you are most comfortable with. The project already
includes [Laravel Sail](https://github.com/laravel/sail) for your convenience.

## Test Data

Test data is provided through seeders. After running the `php artisan migrate --seed` command, your database should be
ready for use.

## Time Constraint

While we understand the time-consuming nature of coding tasks, we kindly request that you limit yourself to a maximum of
two hours on this task (not including any time required to setup your environment). Please do not worry if you can't
complete the task within the stipulated time. Our primary interest is in assessing your problem-solving approach and
your understanding of the Laravel framework and best practices, not necessarily the end product. Any notes about your
strategies for tackling this task, or your thoughts on potential improvements to the code, are greatly appreciated.

## Submitting the Test

The easiest way for all involved to review this test is via GitHub. We request that you clone the project and copy the
code to your own **private repository**.

```sh
git clone --depth 1 git@github.com:townsendmusic/code-test.git tm-code-test
cd tm-code-test
rm -rf .git
git init
git add .
git commit -m 'The test'
```

Once completed please provide access
to [@albanh](https://github.com/albanh), [@alexbirtwell](https://github.com/alexbirtwell)
and [@oddvalue](https://github.com/oddvalue). This can be done under Settings > Collaborators.

Any notes on your strategy or improvements should be added to the repository in a NOTES.md or replace the content of
this readme.
