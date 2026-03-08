<?php
/**
 * Template Name: Submit a Tip
 *
 * Dedicated page template for the tip submission form.
 * Create a page in WordPress with the slug "submit-a-tip" and assign
 * this template via Page Attributes → Template.
 *
 * @package QueerDispatch
 */

get_header();

$submitted = isset( $_GET['tip_submitted'] ) && $_GET['tip_submitted'] === '1';
$error     = isset( $_GET['tip_error'] ) ? sanitize_text_field( $_GET['tip_error'] ) : '';

$categories = array(
    'General',
    'Politics & Policy',
    'Community Events',
    'Health & Wellness',
    'Arts & Culture',
    'Business & Economy',
    'Education',
    'Housing & Homelessness',
    'Violence & Safety',
    'International',
    'Other',
);
?>
<main id="primary" class="content-area">
    <div class="main-content-grid">
        <div class="main-column">
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-page submit-tip-page' ); ?>>

                <header class="single-post-header">
                    <h1 class="single-post-title">
                        <?php esc_html_e( 'Submit a Tip', 'queerdispatch' ); ?>
                    </h1>
                    <p class="submit-tip-intro">
                        <?php esc_html_e( 'Have a story lead, document, or piece of information you think our editors should know about? Use this form to send it securely. You can remain anonymous — we will never publish your name or contact details without your permission.', 'queerdispatch' ); ?>
                    </p>
                </header>

                <?php if ( $submitted ) : ?>
                    <!-- ======================================================
                         SUCCESS STATE
                    ====================================================== -->
                    <div class="tip-success-message" role="alert">
                        <div class="tip-success-icon" aria-hidden="true">✓</div>
                        <h2><?php esc_html_e( 'Thank you — your tip has been received.', 'queerdispatch' ); ?></h2>
                        <p><?php esc_html_e( 'Our editorial team will review it. If you provided a contact email and we need more information, we will be in touch. We do not publish tips or source identities without explicit permission.', 'queerdispatch' ); ?></p>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn">
                            <?php esc_html_e( 'Return to QueerDispatch', 'queerdispatch' ); ?>
                        </a>
                    </div>

                <?php else : ?>
                    <!-- ======================================================
                         ERROR NOTICE (if any)
                    ====================================================== -->
                    <?php if ( $error === 'empty' ) : ?>
                        <div class="tip-error-message" role="alert">
                            <strong><?php esc_html_e( 'Please enter your tip before submitting.', 'queerdispatch' ); ?></strong>
                        </div>
                    <?php elseif ( $error === 'save' ) : ?>
                        <div class="tip-error-message" role="alert">
                            <strong><?php esc_html_e( 'Something went wrong saving your tip. Please try again or email us directly.', 'queerdispatch' ); ?></strong>
                        </div>
                    <?php endif; ?>

                    <!-- ======================================================
                         TIP SUBMISSION FORM
                    ====================================================== -->
                    <form
                        id="tip-submission-form"
                        class="tip-form"
                        method="post"
                        action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
                        enctype="multipart/form-data"
                        novalidate
                    >
                        <?php wp_nonce_field( 'queerdispatch_submit_tip', 'queerdispatch_tip_nonce' ); ?>
                        <input type="hidden" name="action" value="queerdispatch_submit_tip">

                        <!-- Category -->
                        <div class="tip-form-group">
                            <label for="tip_category" class="tip-form-label">
                                <?php esc_html_e( 'Category', 'queerdispatch' ); ?>
                                <span class="tip-form-optional"><?php esc_html_e( '(helps us route your tip to the right editor)', 'queerdispatch' ); ?></span>
                            </label>
                            <select id="tip_category" name="tip_category" class="tip-form-select">
                                <?php foreach ( $categories as $cat ) : ?>
                                    <option value="<?php echo esc_attr( $cat ); ?>">
                                        <?php echo esc_html( $cat ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Tip body — required -->
                        <div class="tip-form-group">
                            <label for="tip_body" class="tip-form-label">
                                <?php esc_html_e( 'Your Tip', 'queerdispatch' ); ?>
                                <span class="tip-form-required" aria-hidden="true">*</span>
                            </label>
                            <textarea
                                id="tip_body"
                                name="tip_body"
                                class="tip-form-textarea"
                                rows="8"
                                required
                                placeholder="<?php esc_attr_e( 'Describe what you know, what you have seen, or what you think we should investigate. Include as much detail as you are comfortable sharing.', 'queerdispatch' ); ?>"
                            ></textarea>
                        </div>

                        <!-- File attachment -->
                        <div class="tip-form-group">
                            <label for="tip_attachment" class="tip-form-label">
                                <?php esc_html_e( 'Attach a File', 'queerdispatch' ); ?>
                                <span class="tip-form-optional"><?php esc_html_e( '(optional — documents, photos, screenshots)', 'queerdispatch' ); ?></span>
                            </label>
                            <input
                                type="file"
                                id="tip_attachment"
                                name="tip_attachment"
                                class="tip-form-file"
                                accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.mp4,.mov"
                            >
                            <p class="tip-form-hint">
                                <?php esc_html_e( 'Accepted: JPG, PNG, GIF, PDF, DOC, DOCX, TXT, MP4, MOV. Maximum size: 10 MB.', 'queerdispatch' ); ?>
                            </p>
                        </div>

                        <!-- Divider -->
                        <div class="tip-form-divider">
                            <span><?php esc_html_e( 'Optional contact details', 'queerdispatch' ); ?></span>
                        </div>

                        <p class="tip-form-hint tip-form-hint--block">
                            <?php esc_html_e( 'You do not need to provide your name or email. If you do, we may contact you for more information — but we will never publish your identity without your explicit permission.', 'queerdispatch' ); ?>
                        </p>

                        <!-- Name / alias -->
                        <div class="tip-form-group">
                            <label for="tip_contact_name" class="tip-form-label">
                                <?php esc_html_e( 'Name or Alias', 'queerdispatch' ); ?>
                                <span class="tip-form-optional"><?php esc_html_e( '(optional)', 'queerdispatch' ); ?></span>
                            </label>
                            <input
                                type="text"
                                id="tip_contact_name"
                                name="tip_contact_name"
                                class="tip-form-input"
                                placeholder="<?php esc_attr_e( 'How should we address you?', 'queerdispatch' ); ?>"
                                autocomplete="off"
                            >
                        </div>

                        <!-- Contact email -->
                        <div class="tip-form-group">
                            <label for="tip_contact_email" class="tip-form-label">
                                <?php esc_html_e( 'Contact Email', 'queerdispatch' ); ?>
                                <span class="tip-form-optional"><?php esc_html_e( '(optional)', 'queerdispatch' ); ?></span>
                            </label>
                            <input
                                type="email"
                                id="tip_contact_email"
                                name="tip_contact_email"
                                class="tip-form-input"
                                placeholder="<?php esc_attr_e( 'We will only contact you if we need more information.', 'queerdispatch' ); ?>"
                                autocomplete="off"
                            >
                        </div>

                        <!-- Submit -->
                        <div class="tip-form-group tip-form-submit-row">
                            <button type="submit" class="btn tip-form-submit">
                                <?php esc_html_e( 'Send Tip to Editors', 'queerdispatch' ); ?>
                            </button>
                            <p class="tip-form-hint">
                                <?php esc_html_e( 'Your submission is sent directly to our editorial team and stored securely on this server. It is never shared with third parties.', 'queerdispatch' ); ?>
                            </p>
                        </div>

                    </form>

                    <!-- ======================================================
                         SIGNAL CONTACT OPTION
                    ====================================================== -->
                    <aside class="tip-signal-note">
                        <div class="tip-signal-header">
                            <span class="tip-signal-icon" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="22" height="22" aria-hidden="true"><path d="M12 0C5.373 0 0 5.373 0 12c0 2.126.557 4.12 1.529 5.847L.057 23.07a.75.75 0 0 0 .932.908l5.056-1.701A11.94 11.94 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 1.5c5.79 0 10.5 4.71 10.5 10.5S17.79 22.5 12 22.5a10.45 10.45 0 0 1-5.22-1.393l-.318-.19-3.29 1.107 1.003-3.373-.207-.33A10.45 10.45 0 0 1 1.5 12C1.5 6.21 6.21 1.5 12 1.5z"/></svg>
                            </span>
                            <h3><?php esc_html_e( 'Need stronger anonymity? Contact us on Signal.', 'queerdispatch' ); ?></h3>
                        </div>
                        <p><?php esc_html_e( 'If you are a whistleblower, a source at risk of retaliation, or simply prefer end-to-end encrypted communication, Signal is the safest way to reach us. Signal does not log metadata and messages can be set to disappear automatically.', 'queerdispatch' ); ?></p>
                        <div class="tip-signal-contacts">
                            <div class="tip-signal-contact">
                                <span class="tip-signal-contact-label"><?php esc_html_e( 'Phone number', 'queerdispatch' ); ?></span>
                                <a href="https://signal.me/#p/+16093343796" class="tip-signal-contact-value" target="_blank" rel="noopener noreferrer">+1 (609) 334-3796</a>
                            </div>
                            <div class="tip-signal-contact">
                                <span class="tip-signal-contact-label"><?php esc_html_e( 'Username', 'queerdispatch' ); ?></span>
                                <a href="https://signal.me/#u/KPP.78" class="tip-signal-contact-value" target="_blank" rel="noopener noreferrer">KPP.78</a>
                            </div>
                        </div>
                        <p class="tip-signal-hint"><?php esc_html_e( 'To contact us via username without revealing your phone number: open Signal, tap the compose icon, tap "Find by username", and enter KPP.78. You can also scan the link above on a device with Signal installed.', 'queerdispatch' ); ?></p>
                    </aside>

                    <!-- ======================================================
                         PRIVACY NOTE
                    ====================================================== -->
                    <aside class="tip-privacy-note">
                        <h3><?php esc_html_e( 'A note on privacy', 'queerdispatch' ); ?></h3>
                        <p><?php esc_html_e( 'This form is served over HTTPS. Your tip is stored in our private database and is only accessible to QueerDispatch editors. We do not use third-party form services. If you need a higher level of anonymity — for example, if you are a whistleblower or are at risk of retaliation — we recommend contacting us via Signal instead.', 'queerdispatch' ); ?></p>
                    </aside>

                <?php endif; ?>

            </article>
        </div>
        <?php get_sidebar(); ?>
    </div>
</main>

<style>
/* ================================================================
   Submit a Tip — page-specific styles
   These inherit all theme CSS variables so they adapt to every style.
================================================================ */
.submit-tip-page .submit-tip-intro {
    font-size: 1.05rem;
    line-height: 1.7;
    color: var(--color-text-muted, var(--color-text));
    margin-bottom: 2rem;
    max-width: 680px;
}

/* Form layout */
.tip-form {
    max-width: 680px;
    margin-bottom: 2.5rem;
}
.tip-form-group {
    margin-bottom: 1.5rem;
}
.tip-form-label {
    display: block;
    font-weight: 700;
    font-size: 0.95rem;
    margin-bottom: 0.4rem;
    color: var(--color-text);
}
.tip-form-optional {
    font-weight: 400;
    font-size: 0.85rem;
    color: var(--color-text-muted, var(--color-text));
    margin-left: 0.3rem;
}
.tip-form-required {
    color: var(--color-accent);
    margin-left: 2px;
}
.tip-form-input,
.tip-form-select,
.tip-form-textarea {
    width: 100%;
    padding: 0.65rem 0.85rem;
    border: 2px solid var(--color-border, rgba(128,128,128,0.3));
    border-radius: 4px;
    background: var(--color-bg-secondary, var(--color-bg));
    color: var(--color-text);
    font-family: inherit;
    font-size: 1rem;
    line-height: 1.5;
    transition: border-color 0.2s;
    box-sizing: border-box;
}
.tip-form-input:focus,
.tip-form-select:focus,
.tip-form-textarea:focus {
    outline: none;
    border-color: var(--color-accent);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-accent) 20%, transparent);
}
.tip-form-textarea {
    resize: vertical;
    min-height: 180px;
}
.tip-form-file {
    display: block;
    font-size: 0.95rem;
    color: var(--color-text);
    margin-top: 0.25rem;
}
.tip-form-hint {
    font-size: 0.82rem;
    color: var(--color-text-muted, var(--color-text));
    margin-top: 0.35rem;
    margin-bottom: 0;
}
.tip-form-hint--block {
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

/* Divider */
.tip-form-divider {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin: 2rem 0 0.75rem;
    color: var(--color-text-muted, var(--color-text));
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}
.tip-form-divider::before,
.tip-form-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--color-border, rgba(128,128,128,0.3));
}

/* Submit row */
.tip-form-submit-row {
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}
.tip-form-submit {
    align-self: flex-start;
    font-size: 1rem;
    padding: 0.75rem 2rem;
}

/* Success message */
.tip-success-message {
    max-width: 600px;
    padding: 2.5rem;
    border: 2px solid var(--color-accent);
    border-radius: 6px;
    background: color-mix(in srgb, var(--color-accent) 8%, var(--color-bg));
    text-align: center;
    margin-bottom: 2rem;
}
.tip-success-icon {
    font-size: 3rem;
    color: var(--color-accent);
    margin-bottom: 1rem;
    line-height: 1;
}
.tip-success-message h2 {
    font-size: 1.4rem;
    margin-bottom: 0.75rem;
    color: var(--color-text);
}
.tip-success-message p {
    color: var(--color-text-muted, var(--color-text));
    margin-bottom: 1.5rem;
    line-height: 1.7;
}

/* Error message */
.tip-error-message {
    max-width: 680px;
    padding: 1rem 1.25rem;
    border-left: 4px solid #cc0000;
    background: color-mix(in srgb, #cc0000 10%, var(--color-bg));
    color: var(--color-text);
    margin-bottom: 1.5rem;
    border-radius: 0 4px 4px 0;
}

/* Privacy note */
.tip-privacy-note {
    max-width: 680px;
    padding: 1.25rem 1.5rem;
    border: 1px solid var(--color-border, rgba(128,128,128,0.3));
    border-radius: 4px;
    background: var(--color-bg-secondary, var(--color-bg));
    margin-top: 2.5rem;
}
.tip-privacy-note h3 {
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 0.5rem;
    color: var(--color-text-muted, var(--color-text));
}
.tip-privacy-note p {
    font-size: 0.88rem;
    line-height: 1.65;
    color: var(--color-text-muted, var(--color-text));
    margin: 0;
}

@media ( max-width: 600px ) {
    .tip-form-submit {
        width: 100%;
        text-align: center;
    }
}

/* Signal contact section */
.tip-signal-note {
    max-width: 680px;
    padding: 1.4rem 1.6rem;
    border: 2px solid #3a76f0;
    border-radius: 6px;
    background: color-mix(in srgb, #3a76f0 8%, var(--color-bg));
    margin-top: 2.5rem;
    margin-bottom: 1.5rem;
}
.tip-signal-header {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    margin-bottom: 0.75rem;
}
.tip-signal-icon {
    color: #3a76f0;
    flex-shrink: 0;
    display: flex;
    align-items: center;
}
.tip-signal-header h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 700;
    color: var(--color-text);
    line-height: 1.3;
}
.tip-signal-note > p {
    font-size: 0.9rem;
    line-height: 1.65;
    color: var(--color-text-muted, var(--color-text));
    margin: 0 0 1rem;
}
.tip-signal-contacts {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.85rem;
}
.tip-signal-contact {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}
.tip-signal-contact-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--color-text-muted, var(--color-text));
}
.tip-signal-contact-value {
    font-size: 1.05rem;
    font-weight: 700;
    color: #3a76f0;
    text-decoration: none;
    letter-spacing: 0.02em;
}
.tip-signal-contact-value:hover {
    text-decoration: underline;
}
.tip-signal-hint {
    font-size: 0.82rem;
    line-height: 1.6;
    color: var(--color-text-muted, var(--color-text));
    margin: 0;
    border-top: 1px solid color-mix(in srgb, #3a76f0 25%, transparent);
    padding-top: 0.75rem;
}
</style>

<?php get_footer(); ?>
