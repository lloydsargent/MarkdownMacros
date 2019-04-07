# MarkdownMacros
Designed for use with PicoCMS. It allows the user to create macros that can be embedded in Markdown files.

Question: *So what is a macro and why would we want to use them? Isn't that the point of Markdown so I don't have to memorize all that stuff?*

Excellent question! I originally decided on macros as a simple solution to a complex problem. In my case it was "how do I get an image to zoom open when I click on it?" The problem with the Markdown in PicoCMS is it is very limited. For example, I can do the following:

    (Cute Baby Squirrel)[assets/cutebabysquirrel.png]

Assuming that all my paths were correct and I had a cute baby squirrel image, then I get the image of a cute baby squirrel. I can even add classes using the following:

    (Cute Baby Squirrel)[assets/cutebabysquirrel.png] {.small}

Okay, cool! But, what if you wanted to add the ability to tap on it and zoom the image? That requires an `onmousedown` and you just will no way to implement that without getting seriously ugly.

That's where macros come in. Let us assume I rewrite this as follows:

    [img_url]assets/cutebabysquirrel.png[img_text]Cute Baby Squirrel[/img]

It's not significantly uglier than the Markdown method, but this is where the magic of the plugins comes in: I can define **exactly** what `[img_url]` means so I can get exactly what I want in the final render. This is what I currently have defined for these three items:

```
'[img_url]':   '<figure id="myImg" onmousedown="zoomImageClick(this)"><img src="'
'[img_text]':  '"><figcaption>'
'[/img]':      '</figcaption></figure>'
```

This beats the heck out of putting all that HTML in our Markdown file!

## Installation

Step 1: Pull `MarkdownMacros` into your `plugins` folder.

Step 2: Add the following to your config.yml file

    custom_macros: mymacros_macros     # name of your macros

Step 3: Create another file named `mymacros_macros.yml` and put it in the `config` file to keep the `config.yml` company. Config files are sociable that way.

Step 4: In your `mymacros_macros.yml` file, put the following:
```
mymacros_macros:
    '[img_url]':    '<figure id="myImg" onmousedown="zoomImageClick(this)"><img src="'
    '[img_text]':   '"><figcaption>'
    '[/img]':       '</figcaption></figure>'
```

Step 5: Start using the macros!

## When to use MarkdownMacros

Any time you feel like putting HTML into your Markdown file to get something to work like it should, resort instead to MarkdownMacros.

## Aren't they inefficient?

They are less efficient than writing HTML, if that is what you mean. Computers, however, are SUPPOSED to eliminate drudgery. The macros do exactly that.

## Can I write HTML and still use the MarkdownMacrosf

Yes.

## Is it errorprone?

Depends. If you type `[img_url]` a lot for no reason, yes. But the same can be said about typing `<div>` (that will make your life REALLY unhappy!). At the moment, I call it a draw.

## Why do you do this?

It's either this or playing Final Fantasy 14. Which, I think it is time to play...