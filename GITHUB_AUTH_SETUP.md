# GitHub Authentication Setup Guide

## Step 1: Create a Personal Access Token (PAT)

1. Go to GitHub: https://github.com/settings/tokens
2. Click "Generate new token" → "Generate new token (classic)"
3. Give it a name: "Water Billing System"
4. Select expiration (recommend: 90 days or custom)
5. Select these scopes:
   - ✅ `repo` (Full control of private repositories)
   - ✅ `workflow` (if you use GitHub Actions)
6. Click "Generate token"
7. **IMPORTANT**: Copy the token immediately (you won't see it again!)

## Step 2: Use the Token

### Option A: Let Git Credential Manager handle it (Recommended)
When you push, Git will prompt for credentials:
- Username: `messenger1976`
- Password: `[paste your Personal Access Token here]`

### Option B: Include token in remote URL (Temporary)
```bash
git remote set-url origin https://[YOUR_TOKEN]@github.com/messenger1976/waterbilling.git
```

### Option C: Use SSH (Alternative)
```bash
git remote set-url origin git@github.com:messenger1976/waterbilling.git
```
Then set up SSH keys in GitHub Settings → SSH and GPG keys

## Step 3: Verify Repository Exists

Make sure the repository exists at:
https://github.com/messenger1976/waterbilling

If it doesn't exist, create it on GitHub first!

## Step 4: Push Your Changes

Once authenticated, run:
```bash
git push origin waterbilling1
```

