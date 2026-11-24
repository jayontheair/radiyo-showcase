# Security design and limitations

Production source separates tenant database credentials and OAuth configuration into excluded files. The repository includes authentication, permission, CSRF and rate-limit helpers. These are implementation patterns, not a claim that the production application has passed a security audit.

The showcase contains no customer records, production database schema, credentials, screenshots or internal endpoints. It uses synthetic fixtures and environment-supplied credentials for the optional voice call. SQL field names and operators are selected from fixed allowlists; values are passed separately for binding. Invalid filters are rejected in this adaptation. A filter is never an authorization boundary.

Before deployment, integrate authorization, tenancy enforcement, input limits, logging without secrets, concurrency-safe winner confirmation, provider consent and retention controls. The standalone draw does not persist winners. The voice adapter sends script text to an external provider; use non-sensitive scripts and an appropriately licensed voice.
