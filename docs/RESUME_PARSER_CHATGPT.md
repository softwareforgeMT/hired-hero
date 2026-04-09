# Resume Parser with OpenAI ChatGPT Integration

## Overview
The `ResumeParserService` has been enhanced to use OpenAI's ChatGPT API for intelligent resume parsing. This provides better accuracy and understanding of resume content compared to regex-based extraction.

## Features

### Smart Extraction
- **Skills**: Accurately identifies both technical and soft skills from resume content
- **Experience**: Extracts years of work experience with better context understanding
- **Companies**: Identifies past employers and organizations
- **Education**: Extracts degrees, certifications, and educational qualifications
- **Job Titles**: Recognizes all positions held
- **Sectors**: Infers industry sectors based on resume content
- **Seniority Level**: Determines career level (entry, mid, senior, executive)

### Fallback Mechanism
If OpenAI API fails or encounters any issues, the service automatically falls back to regex-based extraction to ensure the application continues functioning.

## Configuration

### Required Environment Variables
```env
OPENAI_API_KEY=your-api-key-here
```

The API key is already configured in `config/services.php`:
```php
'openai' => [
    'api_key' => env('OPENAI_API_KEY'),
    'model' => env('OPENAI_MODEL', 'gpt-4-turbo'),
],
```

## Usage

### Basic Resume Parsing
```php
// In a controller or service
$resumeParser = new ResumeParserService();

// Parse resume file
$resumeData = $resumeParser->parseResume('path/to/resume.pdf');

// Returns JSON structure:
{
    "skills": ["PHP", "Laravel", "JavaScript", "React", "AWS"],
    "years_experience": 5,
    "companies": ["TechCorp", "StartupInc", "WebSolutions"],
    "education": ["Bachelor in Computer Science"],
    "job_titles": ["Developer", "Senior Engineer", "Tech Lead"],
    "sectors": ["technology", "software"],
    "seniority_level": "senior",
    "raw_text": "..." // First 5000 characters
}
```

### In Step 4 (Resume Upload)
The `PlacementWizardController::submitStep4()` automatically:
1. Validates the resume file
2. Stores it securely
3. Parses it using ChatGPT
4. Saves extracted data to the placement profile

```php
// Extracted data is automatically saved
$profile->update([
    'resume_path' => $path,
    'resume_data' => $resumeData,
    'has_resume' => true,
    'skills' => $resumeData['skills'],
    'years_experience' => $resumeData['years_experience'],
    'past_companies' => $resumeData['companies'],
    'past_sectors' => $resumeData['sectors'],
]);
```

## Supported File Formats
- **PDF** (.pdf)
- **DOCX** (.docx)
- **DOC** (.doc)
- **TXT** (.txt)

Maximum file size: **5 MB**

## JSON Response Format

### Success Response Structure
```json
{
    "skills": ["array", "of", "skills"],
    "years_experience": 5,
    "companies": ["company1", "company2"],
    "education": ["degree1", "degree2"],
    "job_titles": ["title1", "title2"],
    "sectors": ["sector1", "sector2"],
    "seniority_level": "senior",
    "raw_text": "first 5000 characters of resume text"
}
```

### Field Descriptions

| Field | Type | Description |
|-------|------|-------------|
| skills | array | All technical and soft skills found in resume |
| years_experience | integer\|null | Total years of professional experience |
| companies | array | Organizations where candidate worked |
| education | array | Degrees, certifications, qualifications |
| job_titles | array | All positions and titles held |
| sectors | array | Industry sectors (healthcare, finance, tech, etc.) |
| seniority_level | string | Career level: entry, mid, senior, or executive |
| raw_text | string | First 5000 characters of parsed resume text |

## Error Handling

### Logging
All errors are logged to `storage/logs/laravel.log`:
- PDF parsing errors
- DOCX parsing errors
- TXT parsing errors
- OpenAI API errors
- JSON decode errors

### Fallback Behavior
If ChatGPT extraction fails:
1. Service logs the error
2. Falls back to regex-based extraction
3. Returns structured data with best-effort extraction
4. Application continues without interruption

## API Usage & Costs

### OpenAI Pricing
- Model: GPT-4-Turbo (configurable)
- Approximate cost per resume: $0.02-0.05 USD
- See [OpenAI Pricing](https://openai.com/pricing) for current rates

### Rate Limiting
- Implement request queuing for batch processing
- Add delays between API calls if needed
- Monitor API usage in OpenAI dashboard

## Integration Points

### PlacementProfile Model
Resume data is stored in the `placement_profiles` table:
```php
// Columns:
resume_path       // Path to stored file
resume_data       // Full JSON response from ChatGPT
has_resume        // Boolean flag
skills            // Array of skills
years_experience  // Integer
past_companies    // Array of companies
past_sectors      // Array of sectors
```

### PlacementWizardController
Step 4 automatically handles:
- File validation
- Resume storage
- ChatGPT parsing
- Data persistence

## Best Practices

1. **Batch Processing**: For bulk resume parsing, implement job queue
2. **Caching**: Cache frequently parsed resume structures
3. **Monitoring**: Track API failures and fallback rates
4. **Validation**: Always validate extracted data before using
5. **Privacy**: Ensure secure storage of resume files

## Testing

### Test Resume Parsing
```php
// In tests
$service = new ResumeParserService();

$result = $service->parseResume('tests/samples/resume.pdf');

$this->assertIsArray($result);
$this->assertArrayHasKey('skills', $result);
$this->assertArrayHasKey('years_experience', $result);
```

## Future Enhancements

- [ ] Batch processing with job queues
- [ ] Resume versioning and history
- [ ] Extract salary expectations
- [ ] Language detection and multi-language support
- [ ] Contact information extraction
- [ ] Skill proficiency levels
- [ ] Automated resume validation suggestions
