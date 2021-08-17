<?php return array (
  'facade/ignition' => 
  array (
    'providers' => 
    array (
      0 => 'Facade\\Ignition\\IgnitionServiceProvider',
    ),
    'aliases' => 
    array (
      'Flare' => 'Facade\\Ignition\\Facades\\Flare',
    ),
  ),
  'fideloper/proxy' => 
  array (
    'providers' => 
    array (
      0 => 'Fideloper\\Proxy\\TrustedProxyServiceProvider',
    ),
  ),
  'fruitcake/laravel-cors' => 
  array (
    'providers' => 
    array (
      0 => 'Fruitcake\\Cors\\CorsServiceProvider',
    ),
  ),
  'laravel/tinker' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Tinker\\TinkerServiceProvider',
    ),
  ),
  'mll-lab/laravel-graphql-playground' => 
  array (
    'providers' => 
    array (
      0 => 'MLL\\GraphQLPlayground\\GraphQLPlaygroundServiceProvider',
    ),
  ),
  'nesbot/carbon' => 
  array (
    'providers' => 
    array (
      0 => 'Carbon\\Laravel\\ServiceProvider',
    ),
  ),
  'nunomaduro/collision' => 
  array (
    'providers' => 
    array (
      0 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
    ),
  ),
  'nuwave/lighthouse' => 
  array (
    'aliases' => 
    array (
      'graphql' => 'Nuwave\\Lighthouse\\GraphQL',
    ),
    'providers' => 
    array (
      0 => 'Nuwave\\Lighthouse\\LighthouseServiceProvider',
      1 => 'Nuwave\\Lighthouse\\GlobalId\\GlobalIdServiceProvider',
      2 => 'Nuwave\\Lighthouse\\OrderBy\\OrderByServiceProvider',
      3 => 'Nuwave\\Lighthouse\\Pagination\\PaginationServiceProvider',
      4 => 'Nuwave\\Lighthouse\\Scout\\ScoutServiceProvider',
      5 => 'Nuwave\\Lighthouse\\SoftDeletes\\SoftDeletesServiceProvider',
      6 => 'Nuwave\\Lighthouse\\Validation\\ValidationServiceProvider',
    ),
  ),
);