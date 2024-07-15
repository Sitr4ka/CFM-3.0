**Changelog** (https://github.com/symfony/symfony/compare/v7.1.0...v7.1.1)

 * bug [#57110] [PhpUnitBridge] Fix error handler triggered outside of tests (@HypeMC)
 * bug [#57305] [Validator] do not modify a constraint during validation to not leak its context (@xabbuh)
 * bug [#57297] [FrameworkBundle] not registered definitions must not be modified (@xabbuh)
 * bug [#57234] [String] Fix Inflector for 'hardware' (@podhy)
 * bug [#57224] [Mime] Use streams instead of loading raw message generator into memory (@bytestream)
 * bug [#57284] [Mime] Fix TextPart using an unknown File (@fabpot)
 * bug [#57282] [Scheduler] Throw an exception when no dispatcher has been passed to a Schedule (@fabpot)
 * bug [#57276] Fix autoload configs to avoid warnings when building optimized autoloaders (@Seldaek)
 * bug [#57275] Fix autoload configs to avoid warnings when building optimized autoloaders (@Seldaek)
 * bug [#57263] [SecurityBundle] Fix `container.build_hash` parameter binding (@alexandre-daubois)
 * bug [#57197] [Serializer] Fix denormalizing a collection of union types (@HypeMC)
 * bug [#57188] [DoctrineBridge] Fix `UniqueEntityValidator` with proxy object (@HypeMC)

[PR] https://github.com/symfony/symfony/pull/57306
