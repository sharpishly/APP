{{{header}}}

<!-- Pfands -->
<div class="layout-item">
<section id="tax-rebate" class="py-12">
    <h1>{{{h1}}}</h1>
    <h2>{{{h2}}}</h2>
    <!-- <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b-2 border-gray-300 pb-2">Tax Rebate Application</h2> -->
    <p class="mb-4">
        Apply for a tax rebate related to work expenses. Please fill out the form below.
    </p>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-blue-600 mb-4">Applicant Information</h3>
        <form id="tax-rebate-form" class="space-y-4">
            <div>
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full Name:</label>
                <input type="text" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div>
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div>
                <label for="national-insurance" class="block text-gray-700 text-sm font-bold mb-2">National Insurance Number:</label>
                <input type="text" id="national-insurance" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div>
                <label for="occupation" class="block text-gray-700 text-sm font-bold mb-2">Occupation:</label>
                <input type="text" id="occupation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div>
                <label for="employer" class="block text-gray-700 text-sm font-bold mb-2">Employer:</label>
                <input type="text" id="employer" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <h3 class="text-xl font-semibold text-blue-600 mb-4">Expense Information</h3>
             <div>
                <label for="expense-type" class="block text-gray-700 text-sm font-bold mb-2">Expense Type:</label>
                <select id="expense-type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="uniform">Uniform</option>
                    <option value="tools">Tools & Equipment</option>
                    <option value="travel">Travel Expenses</option>
                    <option value="subscriptions">Professional Subscriptions</option>
                     <option value="flat-rate">Flat Rate Expense</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div id="flat-rate-details" class="hidden">
                <label for="job-title" class="block text-gray-700 text-sm font-bold mb-2">Job Title:</label>
                <input type="text" id="job-title"  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div>
                <label for="expense-amount" class="block text-gray-700 text-sm font-bold mb-2">Expense Amount (£):</label>
                <input type="number" id="expense-amount" min="0" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div>
                <label for="expense-details" class="block text-gray-700 text-sm font-bold mb-2">Expense Details:</label>
                <textarea id="expense-details" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>

            <h3 class="text-xl font-semibold text-blue-600 mb-4">Declaration</h3>
            <p class="mb-4">
                I declare that the information provided in this application is true and complete to the best of my knowledge.
            </p>
            <div>
                <label for="declaration" class="inline-flex items-center">
                    <input type="checkbox" id="declaration" class="form-checkbox h-5 w-5 text-blue-600 rounded" required>
                    <span class="ml-2 text-gray-700 text-sm">I agree to the above declaration.</span>
                </label>
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit Application</button>
             <div id="submission-confirmation" class="mt-4 p-4 bg-green-100 rounded-md border border-green-400 text-green-700 hidden">
                <p>Your application has been submitted successfully. We will contact you shortly.</p>
            </div>
            <div id="submission-error" class="mt-4 p-4 bg-red-100 rounded-md border border-red-400 text-red-700 hidden">
                <p>There was an error submitting your application. Please try again.</p>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const taxRebateForm = document.getElementById('tax-rebate-form');
        const submissionConfirmation = document.getElementById('submission-confirmation');
        const submissionError = document.getElementById('submission-error');
        const expenseTypeSelect = document.getElementById('expense-type');
        const flatRateDetails = document.getElementById('flat-rate-details');
        const jobTitleInput = document.getElementById('job-title');


        expenseTypeSelect.addEventListener('change', () => {
            if (expenseTypeSelect.value === 'flat-rate') {
                flatRateDetails.classList.remove('hidden');
                jobTitleInput.setAttribute('required', '');
            } else {
                flatRateDetails.classList.add('hidden');
                jobTitleInput.removeAttribute('required');
            }
        });

        taxRebateForm.addEventListener('submit', (event) => {
            event.preventDefault();

            // In a real application, you would send this data to a server
            // using fetch or AJAX.  For this example, we'll just simulate a submission.

            // Simulate a successful submission (replace with your actual submission logic)
            setTimeout(() => {
                //submissionConfirmation.classList.remove('hidden'); // Show success message
                 //taxRebateForm.reset(); // Clear the form
                //window.scrollTo({ top: 0, behavior: 'smooth' });
                // Simulate an error (for testing purposes)
                const success = Math.random() < 0.8; // 80% chance of success
                if (success) {
                    submissionConfirmation.classList.remove('hidden');
                    taxRebateForm.reset();
                     window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    submissionError.classList.remove('hidden');
                }


            }, 2000); // Simulate a 2-second delay
        });
    });
</script>

</div>
<!-- Pfands -->

{{{footer}}}