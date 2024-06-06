# Oh Dear Application Monitoring

This repository contains the PHP script for Oh Dear application monitoring, designed to check and report the health status of your server including disk usage and server load.

## Deployment

We use [Deployer](https://deployer.org/) for automated deployments to multiple production servers. The deployment process is triggered automatically when changes are pushed to the `main` branch. For details on the specific production servers and deployment configuration, please refer to the `deploy.yml` file in the repository.

## Getting Started

To get started with this monitoring script, clone the repository, and ensure your server meets the following requirements:
- PHP 8.2+
- Access to shell commands for server load and disk usage checks

After cloning, set up your `.env` file based on the `.env.example` provided, specifying your Oh Dear health check secret and any other required environment variables.

Also setup your `tests/http-client.private.env.json` with 
```json
{
  "development": {
    "secret": "${secret-code}"
  }
}
```

## Deployment Process

The deployment to production servers is configured in `deploy.yml`. Ensure you have Deployer installed and configured correctly to handle deployments. For more information on using Deployer, visit the [official documentation](https://deployer.org/docs).
