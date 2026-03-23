# Security Policy

## Supported Versions

This project does not currently maintain multiple supported release lines.

Security fixes are expected to land on the latest version in the default branch.

## Reporting a Vulnerability

If you discover a security issue, please report it privately and do not open a public issue or pull request with exploit details.

Include the following when possible:

- a clear description of the issue
- affected area or file
- reproduction steps
- expected impact
- any suggested mitigation

## Disclosure Guidelines

Please help keep users safe by following these steps:

1. Report the issue privately first.
2. Allow time to validate and prepare a fix.
3. Avoid sharing proof-of-concept details publicly until the issue is addressed.

## Scope

Examples of issues that should be reported through the private security process:

- authentication bypass
- authorization flaws
- privilege escalation
- sensitive data exposure
- insecure default credentials
- remote code execution
- SQL injection, XSS, CSRF, or similar vulnerabilities

## Operational Notes

- Do not commit real credentials, tokens, or private keys to this repository.
- Bootstrap admin credentials should be supplied through environment variables.
- If `BOOTSTRAP_ADMIN_PASSWORD` is not configured, the bootstrap admin seeder will skip seeding.

## Response Expectations

Best effort will be made to:

- acknowledge valid reports promptly
- confirm whether the issue is reproducible
- prepare and ship a fix when warranted
- update documentation when operational guidance changes
