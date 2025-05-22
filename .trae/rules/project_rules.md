# 📘 PROJECT_RULES.md

This file defines the **core development rules and best practices** that the AI agent must follow for building and updating this WordPress plugin.

---

## 📁 1. FOLDER & FILE STRUCTURE

Maintain a clean and modular WordPress plugin structure:

```

whatsapp-livechat-quote/
│
├── includes/              # Core logic (functions, classes, hooks)
│
├── assets/
│   ├── css/               # Stylesheets
│   └── js/                # JavaScript files
│
├── templates/             # HTML output or shortcode templates
│
├── uninstall.php          # Cleanup tasks on plugin uninstall
│
├── whatsapp-livechat-quote.php   # Main plugin bootstrap file (with header)

```

- All business logic must go inside `includes/`.
- Use `assets/` only for CSS and JS. Load conditionally when needed.
- Use `templates/` for HTML output separated from PHP logic.

---

## 🧠 2. CODE PRACTICES

- ✅ Follow **WordPress coding standards** for PHP and JS.
- ✅ Use **proper naming conventions** with plugin prefix (e.g., `wlq_`).
- ✅ Escape output using functions like `esc_html()`, `esc_attr()`.
- ✅ Sanitize all inputs using `sanitize_text_field()`, `sanitize_email()`, etc.
- ✅ Always check capabilities before saving or accessing data (e.g., `current_user_can()`).
- ✅ Use nonces for all form submissions or admin actions.

---

## 🧩 3. FUNCTIONAL RULES

- Create and register shortcodes/hooks in `includes/`.
- If creating settings pages:
  - Use `add_menu_page()` properly.
  - Store settings using `update_option()` and retrieve with `get_option()`.
- For quote requests:
  - Validate and sanitize all form inputs.
  - Optionally send via email or store using `post_type` or `option`.

---

## ⚠️ 4. AI-SPECIFIC INSTRUCTIONS

- 🧠 Focus on **clean, readable, and reusable** code.
- 🧩 Always organize logic and avoid writing everything in the main plugin file.
- 🛑 Do **not** create new folders or systems outside the defined structure.
- 🛑 Do **not** hardcode sensitive information.
- ✅ Make sure all new features follow WordPress best practices.

---

## ✅ 5. QUALITY CHECKLIST

Before submitting or finalizing changes:
- [ ] Plugin activates without errors or warnings.
- [ ] All inputs are sanitized, and outputs escaped.
- [ ] Files and functions are properly organized.
- [ ] Admin or frontend functionality is not broken.
- [ ] Code is readable and maintainable.

---

## 🏁 FINAL NOTE

> This rulebook is mandatory for all AI-generated logic related to this plugin. Always prioritize organization, security, and maintainability.
