# RadiYO! | Engineering showcase

[RadiYO!](https://radiyo.me) is a multi-tenant CRM for radio stations, built with PHP, JavaScript, MySQL and Bootstrap. This portfolio presents adapted, standalone examples of selected implementation patterns, not the production source tree. Examples use fictional data and do not require access to a station.

## Start here

| Example | Engineering focus |
|---|---|
| [Voice AI](examples/voice.php) | ElevenLabs integration, provider boundary, injected credentials and error handling |
| [Contact segmentation](examples/filter.php) | Allowlisted field/operator vocabulary, grouped predicates and bound SQL values |
| [Competition engine](examples/draw.php) | Weighted random selection, eligibility and recent-winner exclusion |
| [Campaign analytics](examples/pace.php) | Shared business logic for progress reporting |

Read the [case study](docs/case-study.md), [architecture](docs/architecture.md) and [security design](docs/security-design.md).

## Run locally

The optional live voice demo requires PHP 8.1+, PHP cURL and your own ElevenLabs credentials. Set `ELEVENLABS_API_KEY` and `ELEVENLABS_VOICE_ID` in your environment, then run `php examples/voice-demo.php`. It writes `sample.mp3` locally and may consume paid credits. Do not commit credentials or generated audio.

## Scope and provenance

The examples are portfolio adaptations of existing RadiYO! functionality, prepared with AI assistance. They are not verbatim production files or a deployable CRM. Voice synthesis is implemented in the product code; live deployment and usage metrics for this feature are not asserted here. RAG is a development direction, not a shipped capability claimed by this portfolio. The live provider call has not been tested as part of this package.

Copyright retained. See [licence](LICENSE).
