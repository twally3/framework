<?php


require_once __DIR__ . '/../App/Config/database.php';

require_once __DIR__ . '/../vendor/max/framework/src/core/Exceptions/ClassIsNotInstantiableException.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/Exceptions/DependencyNameAlreadyInUseException.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/Exceptions/FacadeDoesNotImplimentMethodException.php';

require_once __DIR__ . '/../vendor/max/framework/src/core/foundation/container.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/foundation/application.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/support/ServiceProviderInterface.php';

require_once __DIR__ . '/../vendor/max/framework/src/core/providers/TeaProvider.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/providers/SessionRequestProvider.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/providers/DatabaseProvider.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/providers/RouterProvider.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/providers/RequestProvider.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/providers/FileRequestProvider.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/providers/MigrationsProvider.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/providers/SchemaProvider.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/providers/TableProvider.php';

require_once __DIR__ . '/../app/bootstrap/providers/QuxProvider.php';

require_once __DIR__ . '/../vendor/max/framework/src/core/support/facades/facade.php';

require_once __DIR__ . '/../vendor/max/framework/src/core/support/facades/RouterFacade.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/support/facades/TeaFacade.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/support/facades/SessionRequestFacade.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/support/facades/DatabaseFacade.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/support/facades/SchemaFacade.php';

require_once __DIR__ . '/../app/bootstrap/facades/QuxFacade.php';

require_once __DIR__ . '/../vendor/max/framework/src/core/foundation/Console/CliKernel.php';

require_once __DIR__ . '/../vendor/max/framework/src/core/foundation/http/HTTPKernel.php';
require_once __DIR__ . '/../app/http/kernel.php';

// LOADING ALL THE ACTUALL CLASSES!
require_once __DIR__ . '/../vendor/max/framework/src/core/http/SessionRequest.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/http/Router.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/http/Request.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/http/FileRequest.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/http/Controller.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/database/database.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/database/migrations.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/database/schema.php';
require_once __DIR__ . '/../vendor/max/framework/src/core/database/table.php';

require_once __DIR__ . '/../vendor/max/framework/src/core/Render/Tea.php';

require_once __DIR__ . '/../app/bootstrap/includes/Qux.php';