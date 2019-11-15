# simple-acl
A simple and highly flexible and customizable access control library for PHP 

Entities
----------
* Resource: an item on which an **action** will be denied or allowed to be performed on.
It is just a case-insensitive string as far as this package is concerned.

* Action: represents a task that can be performed on a **resource**. 
It is just a case-insensitive string as far as this package is concerned.

* Permission: an object defining whether or not a **PermissionableEntity** 
is allowed or not allowed to perform an **action** on a particular **resource**.
This object will allow additional assertions (to test if a **PermissionableEntity**
 is allowed or not allowed to perform an **action** on a particular **resource**) to 
 be injected via a callback.

* PermissionableEntity: has one or more unique **permissions** and can have one or more other unique 
**PermissionableEntities** related to it as parents and consequently inherit permissions from its parent 
relations. Each parent can have parents and those parents can in turn have parents and so on. An entity 
cannot become a parent of another entity that is already its parent. Each parent of a **PermissionableEntity** 
must have a unique id value. Permissions directly associated with an entity have higher priority than those 
inherited from parent related entities. Each entity must have a unique case-insensitive string identifier (an
Entities Repository maybe introduced to guarantee this uniqueness). A **permission** is unique to a 
**PermissionableEntity** if it is the only permission associated with the entity having a specific 
**action** and **resource** value pair. In a real world application an entity can represent various 
things such as a user or a group (that users can belong to).
 


Ideas / Work in Progress

```
SimpleAcl\PermissionableEntityInterface:
	__construct(string $id, SimpleAcl\PermissionsInterface $perms=null)
    addParentEntity(SimpleAcl\PermissionableEntityInterface $entity): self | throw exception if unsuccesfull
    addParentEntities(SimpleAcl\PermissionableEntitiesInterface $entities): self | throw exception if unsuccesfull
	isEqualTo(SimpleAcl\PermissionableEntityInterface $entity): bool
    isChildOf(SimpleAcl\PermissionableEntityInterface $entity): bool
    isChildOfEntityWithId(string $entityId): bool
	getId(): string
	getParentEntities(): SimpleAcl\PermissionableEntitiesInterface
    removeParentIfExists(SimpleAcl\PermissionableEntityInterface $entity): self | throw exception if unsuccesfull
    removeParentsThatExist(SimpleAcl\PermissionableEntitiesInterface $entities): self | throw exception if unsuccesfull

    addPermission(SimpleAcl\PermissionInterface $perm): self | throw exception if unsuccesfull
    addPermissions(SimpleAcl\PermissionsInterface $perms): self | throw exception if unsuccesfull
    getPermissions(): SimpleAcl\PermissionsInterface
    getInheritedPermissions(): SimpleAcl\PermissionsInterface
     
    removePermissionIfExists(SimpleAcl\PermissionInterface $perm): self | throw exception if unsuccesfull
    removePermissionsThatExist(SimpleAcl\PermissionsInterface $perms): self | throw exception if unsuccesfull
	
	static createCollection(): SimpleAcl\PermissionableEntitiesInterface



SimpleAcl\PermissionInterface
    getAction(): string
    static getAllActionsIdentifier(): string [a special string representing all performable actions on a resource]

    getResource(): string
    static getAllResoucesIdentifier(): string [a special string representing all resources in the system]

    isActionAllowedOnResource(string $action, string $resource, callable $additionalAssertions=null, ...$argsForCallback): bool
    toggleAllowActionOnResource(bool $allowActionOnResource): self
    getAllowActionOnResource(): bool
	
	isEqualTo(SimpleAcl\PermissionInterface $permission): bool

    __construct(string $action, string $resource, bool $allowActionOnResource=true, callable $additionalAssertions=null, ...$argsForCallback)

	static createCollection(): SimpleAcl\PermissionsInterface

Collections: Enforce type-check when adding items to each collection
    SimpleAcl\CollectionInterface extends \ArrayAccess, \Countable, \IteratorAggregate
		__construct(...$items) // must be of the same type
	
        SimpleAcl\PermissionableEntitiesInterface  extends SimpleAcl\CollectionInterface
			hasEntity(SimpleAcl\PermissionableEntityInterface $entity): bool 
			
        SimpleAcl\PermissionsInterface  extends SimpleAcl\CollectionInterface
			hasPermission(SimpleAcl\PermissionInterface $perm): bool 
			isActionAllowedOnResource(string $action, string $resource, callable $additionalAssertions=null, ...$argsForCallback): bool
```
