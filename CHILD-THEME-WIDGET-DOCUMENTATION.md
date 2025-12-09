# Child Theme Widget Process Documentation

## Overview

This documentation outlines the process for creating, managing, and maintaining custom SiteOrigin widgets within UMC child themes. Custom widgets are built directly into child themes when they are specific to a particular department, college, or client, providing flexibility for custom fields, layouts, and designs.

---

## 1. When to Use Child Theme Widgets

### Use Child Theme Widgets When:

- **Widget is client-specific**: The widget is designed for a specific department, college, or client (e.g., College of Science, College of Humanities)
- **Custom functionality required**: The widget needs custom fields, layouts, or designs that are unique to that client
- **Single-use widget**: The widget will only be used within that specific child theme
- **Rapid development needed**: Quick iteration and customization is required for a specific client's needs
- **Theme-specific styling**: The widget requires styling that is tightly coupled with the child theme's design system

### Do NOT Use Child Theme Widgets When:

- **Multi-theme usage**: The widget needs to be used across multiple child themes
- **Reusable component**: The widget is a generic component that could benefit other clients
- **Complex dependencies**: The widget requires extensive external libraries or complex setup that would be better managed as a plugin

---

## 2. How to Install/Create/Use Child Theme Widgets

### Prerequisites

1. **SiteOrigin Widgets Bundle** plugin must be installed and active
2. **SiteOrigin Page Builder** plugin must be installed and active (if using in page builder)
3. Child theme must have the `siteorigin-widgets/load-widgets.php` file included in `functions.php`

### Step 1: Enable Widget Loading in Child Theme

In your child theme's `functions.php`, uncomment or add the widget loader:

```php
//LOAD SITEORIGIN WIDGETS
include_once( 'siteorigin-widgets/load-widgets.php' );
```

### Step 2: Create Widget Directory Structure

Create a new folder for your widget in the `siteorigin-widgets/` directory following this structure:

```
siteorigin-widgets/
└── your-widget-name/
    ├── uu-your-widget-name.php          (Main widget class file)
    ├── tpl/
    │   └── uu-your-widget-name-template.php  (Template file)
    ├── styles/
    │   └── default.less                 (Optional: LESS styles)
    └── assets/                          (Optional: Images, icons, etc.)
        └── banner.svg
```

### Step 3: Register Widget in load-widgets.php

Add your widget to the `$custom_widgets` array in `siteorigin-widgets/load-widgets.php`:

```php
$custom_widgets = array(
    'theme-starter-widget' => 'Theme_Starter_Widget',
    'your-widget-name' => 'Your_Widget_Class_Name',  // Add your widget here
    // Add additional widgets here as needed
);
```

**Important**: 
- The array key (`'your-widget-name'`) must match the folder name
- The array value (`'Your_Widget_Class_Name'`) must match the PHP class name
- The widget file must be named `uu-your-widget-name.php` (with `uu-` prefix)

### Step 4: Create Widget Class File

Create `uu-your-widget-name.php` in your widget folder:

```php
<?php
/*
Widget Name: U of U Your Widget Name
Description: Brief description of what this widget does.
Author: Your Name
Author URI: https://umc.utah.edu
*/

class Your_Widget_Class_Name extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            'your-widget-name', // Widget ID (must match folder name)
            __('Your Widget Display Name', 'your-widget-text-domain'), // Widget Name
            array(
                'description' => __('Widget description for admin.', 'your-widget-text-domain'),
            ),
            array(), // Control options
            // Form fields (custom fields for the widget)
            array(
                'your_field_name' => array(
                    'type' => 'text',
                    'label' => __('Field Label', 'your-widget-text-domain'),
                    'default' => 'default value',
                    'description' => __('Field description.', 'your-widget-text-domain'),
                ),
                // Add more fields as needed
            ),
            get_stylesheet_directory() . '/siteorigin-widgets/your-widget-name'
        );
    }

    function get_template_name($instance) {
        return 'uu-your-widget-name-template'; // Must match template file name (without .php)
    }

    function get_template_dir($instance) {
        return 'tpl';
    }

    function widget($args, $instance) {
        echo $args['before_widget']; // SiteOrigin wrapper div
        $template_path = get_stylesheet_directory() . '/siteorigin-widgets/your-widget-name/tpl/uu-your-widget-name-template.php';
        
        if (file_exists($template_path)) {
            include $template_path;
        } else {
            echo '<div style="color: red;">Error: Template file not found at ' . esc_html($template_path) . '</div>';
        }
        echo $args['after_widget']; // Close wrapper
    }
}
```

### Step 5: Create Template File

Create `tpl/uu-your-widget-name-template.php`:

```php
<?php
// Access widget instance data
$your_field_name = $instance['your_field_name'] ?? '';

// Output your widget HTML
if (!empty($your_field_name)) {
    echo '<div class="your-widget-class">';
    echo esc_html($your_field_name);
    echo '</div>';
}
?>
```

### Step 6: Using the Widget

1. **In Page Builder**: 
   - Edit a page with SiteOrigin Page Builder
   - Click "Add Widget"
   - Look for the tab named after your child theme (e.g., "UMC Theme Three Child Widgets")
   - Select your widget

2. **In Widget Areas**:
   - Go to Appearance > Widgets
   - Find your widget in the available widgets list
   - Drag it to your desired widget area

### Step 7: Styling (Optional)

If you need custom styles, you can:

1. **Use LESS files** (for admin preview):
   - Create/edit `styles/default.less` in your widget folder
   - Note: LESS processing is disabled on the frontend by default in this setup

2. **Use child theme CSS** (recommended):
   - Add styles to `css/custom.css` in your child theme
   - Target your widget using its class or ID

---

## 3. Consistent Naming Convention for Child Theme Widgets

### File and Folder Naming

All widget-related files and folders must follow these conventions:

#### Widget Folder Name
- **Format**: `kebab-case` (lowercase with hyphens)
- **Pattern**: Descriptive name without prefixes
- **Examples**: 
  - `department-banner`
  - `faculty-spotlight`
  - `program-highlight`
  - `news-feed`

#### Widget PHP Class File
- **Format**: `uu-{folder-name}.php`
- **Pattern**: Always prefix with `uu-` followed by the folder name
- **Examples**:
  - Folder: `department-banner` → File: `uu-department-banner.php`
  - Folder: `faculty-spotlight` → File: `uu-faculty-spotlight.php`

#### Widget PHP Class Name
- **Format**: `PascalCase` with underscores between words
- **Pattern**: Descriptive, no `UU_` or `UU_` prefix needed
- **Examples**:
  - `Department_Banner_Widget`
  - `Faculty_Spotlight_Widget`
  - `Program_Highlight_Widget`

#### Template File Name
- **Format**: `uu-{folder-name}-template.php`
- **Pattern**: Always prefix with `uu-`, include `-template` suffix
- **Location**: Must be in `tpl/` subdirectory
- **Examples**:
  - `tpl/uu-department-banner-template.php`
  - `tpl/uu-faculty-spotlight-template.php`

#### Widget ID (in constructor)
- **Format**: Must match the folder name exactly
- **Pattern**: Same as folder name (kebab-case)
- **Example**: `'department-banner'`

#### Text Domain
- **Format**: `{folder-name}-text-domain` or `{child-theme-name}-widgets`
- **Pattern**: Consistent across all strings in the widget
- **Example**: `'department-banner-text-domain'` or `'umctheme3-child-widgets'`

### Naming Examples

**Complete Example: Department Banner Widget**

```
Folder: department-banner
├── uu-department-banner.php              (Class: Department_Banner_Widget)
├── tpl/
│   └── uu-department-banner-template.php
├── styles/
│   └── default.less
└── assets/
    └── banner.svg
```

**Registration in load-widgets.php:**
```php
$custom_widgets = array(
    'department-banner' => 'Department_Banner_Widget',
);
```

**Widget ID in constructor:**
```php
parent::__construct(
    'department-banner',  // Must match folder name
    // ...
);
```

### Field Naming Convention

For widget form fields, use this pattern:

- **Format**: `{prefix}_{descriptive_name}`
- **Prefix**: Use `uu_` for all fields to avoid conflicts
- **Examples**:
  - `uu_banner_title`
  - `uu_faculty_name`
  - `uu_program_description`

### CSS Class Naming

When adding CSS classes to widget output:

- **Format**: `uu-{widget-name}-{element}`
- **Examples**:
  - `uu-department-banner-container`
  - `uu-faculty-spotlight-image`
  - `uu-program-highlight-title`

---

## 4. Version Control Process for Child Theme Widgets

### Git Workflow

#### Initial Setup

1. **Create widget in development environment**
   - Develop and test widget locally
   - Ensure all files follow naming conventions
   - Test widget in both Page Builder and Widget Areas

2. **Commit widget files**
   ```bash
   git add siteorigin-widgets/your-widget-name/
   git add siteorigin-widgets/load-widgets.php
   git commit -m "Add [widget-name] widget for [client-name]"
   ```

#### File Structure in Version Control

All widget files should be tracked:

```
siteorigin-widgets/
├── load-widgets.php                    (Tracked - contains widget registry)
├── theme-starter-widget/               (Tracked - example widget)
│   ├── uu-theme-starter-widget.php
│   ├── tpl/
│   │   └── uu-theme-starter-widget-template.php
│   ├── styles/
│   │   └── default.less
│   └── assets/                         (Tracked - if used)
└── your-widget-name/                   (Tracked - your new widget)
    ├── uu-your-widget-name.php
    ├── tpl/
    │   └── uu-your-widget-name-template.php
    ├── styles/
    │   └── default.less
    └── assets/                         (Tracked - if used)
```

#### What to Track

✅ **DO Track:**
- All PHP files (widget class, templates)
- LESS files (if used)
- Asset files (images, SVGs, icons)
- `load-widgets.php` (widget registry)
- Any custom JavaScript files

❌ **DON'T Track:**
- Compiled CSS files (if LESS is processed)
- Cache files
- Temporary files
- `.DS_Store` files (add to `.gitignore`)

#### Commit Message Convention

Use descriptive commit messages:

```
Add [widget-name] widget for [client-name]

- Description of widget functionality
- Key features or fields included
- Any dependencies or requirements
```

**Example:**
```
Add department-banner widget for College of Science

- Custom banner widget with title, subtitle, and CTA button
- Supports custom background images
- Includes responsive design for mobile/tablet/desktop
```

#### Branch Strategy

1. **For new widgets**: Create feature branch
   ```bash
   git checkout -b feature/add-department-banner-widget
   ```

2. **For widget updates**: Create update branch
   ```bash
   git checkout -b update/department-banner-widget
   ```

3. **Merge to master**: After testing and review

#### Updating Existing Widgets

When updating a widget:

1. **Make changes** to widget files
2. **Update version** in widget header comment (if tracking versions)
3. **Test thoroughly** in development
4. **Commit with descriptive message**:
   ```bash
   git commit -m "Update [widget-name]: [description of changes]"
   ```

#### Removing Widgets

When removing a widget:

1. **Remove widget folder** from `siteorigin-widgets/`
2. **Remove entry** from `$custom_widgets` array in `load-widgets.php`
3. **Commit removal**:
   ```bash
   git rm -r siteorigin-widgets/widget-name/
   git add siteorigin-widgets/load-widgets.php
   git commit -m "Remove [widget-name] widget (no longer needed)"
   ```

#### Deployment Considerations

1. **Before deploying**:
   - Verify all widgets are registered in `load-widgets.php`
   - Test widget loading on staging environment
   - Check for any missing asset files

2. **After deploying**:
   - Clear SiteOrigin widget cache (handled automatically by code)
   - Verify widgets appear in Page Builder
   - Test widget functionality

#### Widget Registry Maintenance

The `$custom_widgets` array in `load-widgets.php` is the single source of truth. Always:

- Keep it alphabetically sorted (optional but recommended)
- Remove entries when widgets are deleted
- Ensure folder names match array keys exactly
- Ensure class names match array values exactly

**Example of well-maintained registry:**
```php
$custom_widgets = array(
    'department-banner' => 'Department_Banner_Widget',
    'faculty-spotlight' => 'Faculty_Spotlight_Widget',
    'program-highlight' => 'Program_Highlight_Widget',
    'theme-starter-widget' => 'Theme_Starter_Widget', // Keep as example
);
```

---

## Quick Reference Checklist

### Creating a New Widget

- [ ] Widget folder created with kebab-case name
- [ ] Widget class file named `uu-{folder-name}.php`
- [ ] Widget class extends `SiteOrigin_Widget`
- [ ] Widget ID matches folder name
- [ ] Template file created in `tpl/` directory
- [ ] Template file named `uu-{folder-name}-template.php`
- [ ] Widget registered in `load-widgets.php`
- [ ] All field names prefixed with `uu_`
- [ ] Text domain consistent throughout widget
- [ ] Widget tested in Page Builder
- [ ] Widget tested in Widget Areas
- [ ] Files committed to version control

### Naming Checklist

- [ ] Folder: kebab-case (e.g., `department-banner`)
- [ ] Class file: `uu-{folder-name}.php`
- [ ] Class name: PascalCase (e.g., `Department_Banner_Widget`)
- [ ] Template: `uu-{folder-name}-template.php`
- [ ] Widget ID: matches folder name
- [ ] Fields: prefixed with `uu_`

---

## Troubleshooting

### Widget Not Appearing

1. Check `load-widgets.php` is included in `functions.php`
2. Verify widget is registered in `$custom_widgets` array
3. Ensure folder name matches array key
4. Ensure class name matches array value
5. Check file naming matches convention (`uu-` prefix)
6. Clear SiteOrigin cache (code handles this automatically)
7. Check PHP error logs for class loading errors

### Template Not Found

1. Verify template file exists in `tpl/` directory
2. Check template file name matches `get_template_name()` return value
3. Ensure template file has `.php` extension
4. Check file permissions

### Styling Issues

1. LESS files are only processed in admin (by design)
2. Add frontend styles to `css/custom.css`
3. Use browser inspector to verify CSS classes are applied
4. Check for CSS specificity conflicts

---

## Additional Resources

- [SiteOrigin Widgets Documentation](https://siteorigin.com/docs/widgets-bundle/)
- [SiteOrigin Widget API Reference](https://siteorigin.com/docs/widgets-bundle/widget-api/)
- UMC Theme Development Standards
- WordPress Coding Standards

---

**Document Version**: 1.0  
**Last Updated**: 2024  
**Maintained By**: UMC Development Team

