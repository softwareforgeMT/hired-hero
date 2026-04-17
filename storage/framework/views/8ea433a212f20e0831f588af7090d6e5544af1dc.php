<?php $__env->startComponent('mail::message'); ?>
# Your AI-Powered Resume is Ready! 🎉

Hi <?php echo e($user->name); ?>,

Great news! Your professional resume has been successfully created using our AI-powered Resume Builder. Your resume is now ready to use in job applications!

## What's Included

✅ Professional <?php echo e($resume->template_name); ?> template
✅ AI-optimized formatting for ATS (Applicant Tracking Systems)
✅ All your skills and experience highlights
✅ Ready to download and share

## Next Steps

1. **Review your resume** - Check it over to make sure everything looks perfect
2. **Download your resume** - Get the PDF version ready for applications
3. **Use in applications** - Start applying to jobs with your professional resume
4. **Continue with job matching** - Complete the wizard to get matched with relevant opportunities

<?php $__env->startComponent('mail::button', ['url' => $viewUrl]); ?>
View Your Resume
<?php echo $__env->renderComponent(); ?>

Or [download the PDF](<?php echo e($downloadUrl); ?>) directly.

## Resume Information

- **Template**: <?php echo e($resume->template_name); ?>

- **Created**: <?php echo e($resume->created_at->format('F j, Y')); ?>


## Need Help?

If you encounter any issues with your resume or need to make changes, you can:
- Edit your resume in our Resume Builder
- Contact our support team
- Visit our help center

---

Thank you for using HiredHero's Resume Builder. Good luck with your job search! 🚀

Best regards,
**The HiredHero Team**

<?php echo $__env->renderComponent(); ?>
<?php /**PATH D:\Herd-Projects\HiredHero-Job-Match\resources\views/emails/resume-created.blade.php ENDPATH**/ ?>