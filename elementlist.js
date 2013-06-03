
var ApiGen = ApiGen || {};
ApiGen.elements = [["c","Avisota\\Config"],["c","Avisota\\Message\\MessageInterface"],["m","Avisota\\Message\\MessageInterface::createSwiftMessage()"],["m","Avisota\\Message\\MessageInterface::getBlindCopyRecipient()"],["m","Avisota\\Message\\MessageInterface::getCopyRecipient()"],["m","Avisota\\Message\\MessageInterface::getFrom()"],["m","Avisota\\Message\\MessageInterface::getRecipient()"],["m","Avisota\\Message\\MessageInterface::getRecipientDetails()"],["m","Avisota\\Message\\MessageInterface::getReplyTo()"],["m","Avisota\\Message\\MessageInterface::getSender()"],["m","Avisota\\Message\\MessageInterface::getSubject()"],["c","Avisota\\Message\\NativeMessage"],["p","Avisota\\Message\\NativeMessage::$message"],["m","Avisota\\Message\\NativeMessage::__construct()"],["m","Avisota\\Message\\NativeMessage::createSwiftMessage()"],["m","Avisota\\Message\\NativeMessage::getBlindCopyRecipient()"],["m","Avisota\\Message\\NativeMessage::getCopyRecipient()"],["m","Avisota\\Message\\NativeMessage::getFrom()"],["m","Avisota\\Message\\NativeMessage::getRecipient()"],["m","Avisota\\Message\\NativeMessage::getRecipientDetails()"],["m","Avisota\\Message\\NativeMessage::getReplyTo()"],["m","Avisota\\Message\\NativeMessage::getSender()"],["m","Avisota\\Message\\NativeMessage::getSubject()"],["m","Avisota\\Message\\NativeMessage::serialize()"],["m","Avisota\\Message\\NativeMessage::unserialize()"],["c","Avisota\\Queue\\ArchivingQueueInterface"],["m","Avisota\\Queue\\ArchivingQueueInterface::cleanup()"],["m","Avisota\\Queue\\ArchivingQueueInterface::faultyMessages()"],["m","Avisota\\Queue\\ArchivingQueueInterface::sendCount()"],["m","Avisota\\Queue\\ArchivingQueueInterface::successfullyMessages()"],["c","Avisota\\Queue\\NoOpQueueExecutionDecider"],["m","Avisota\\Queue\\NoOpQueueExecutionDecider::accept()"],["c","Avisota\\Queue\\QueueExecutionConfig"],["p","Avisota\\Queue\\QueueExecutionConfig::$decider"],["p","Avisota\\Queue\\QueueExecutionConfig::$limit"],["m","Avisota\\Queue\\QueueExecutionConfig::getDecider()"],["m","Avisota\\Queue\\QueueExecutionConfig::getLimit()"],["m","Avisota\\Queue\\QueueExecutionConfig::setDecider()"],["m","Avisota\\Queue\\QueueExecutionConfig::setLimit()"],["c","Avisota\\Queue\\QueueExecutionDeciderInterface"],["m","Avisota\\Queue\\QueueExecutionDeciderInterface::accept()"],["c","Avisota\\Queue\\QueueInterface"],["m","Avisota\\Queue\\QueueInterface::enqueue()"],["m","Avisota\\Queue\\QueueInterface::execute()"],["m","Avisota\\Queue\\QueueInterface::isEmpty()"],["m","Avisota\\Queue\\QueueInterface::length()"],["c","Avisota\\Queue\\QueueManager"],["m","Avisota\\Queue\\QueueManager::enqueue()"],["c","Avisota\\Queue\\SimpleDatabaseQueue"],["p","Avisota\\Queue\\SimpleDatabaseQueue::$connection"],["p","Avisota\\Queue\\SimpleDatabaseQueue::$messageSerializer"],["p","Avisota\\Queue\\SimpleDatabaseQueue::$tableName"],["m","Avisota\\Queue\\SimpleDatabaseQueue::__construct()"],["m","Avisota\\Queue\\SimpleDatabaseQueue::createTableSchema()"],["m","Avisota\\Queue\\SimpleDatabaseQueue::enqueue()"],["m","Avisota\\Queue\\SimpleDatabaseQueue::execute()"],["m","Avisota\\Queue\\SimpleDatabaseQueue::getMessageSerializer()"],["m","Avisota\\Queue\\SimpleDatabaseQueue::isEmpty()"],["m","Avisota\\Queue\\SimpleDatabaseQueue::length()"],["m","Avisota\\Queue\\SimpleDatabaseQueue::setMessageSerializer()"],["c","Avisota\\Recipient"],["p","Avisota\\Recipient::$details"],["p","Avisota\\Recipient::$email"],["m","Avisota\\Recipient::__construct()"],["m","Avisota\\Recipient::getDetails()"],["m","Avisota\\Recipient::getEmail()"],["m","Avisota\\Recipient::setDetails()"],["m","Avisota\\Recipient::setEmail()"],["c","Avisota\\Recipient\\MutableRecipient"],["p","Avisota\\Recipient\\MutableRecipient::$data"],["m","Avisota\\Recipient\\MutableRecipient::__construct()"],["m","Avisota\\Recipient\\MutableRecipient::get()"],["m","Avisota\\Recipient\\MutableRecipient::getDetails()"],["m","Avisota\\Recipient\\MutableRecipient::getEmail()"],["m","Avisota\\Recipient\\MutableRecipient::getKeys()"],["m","Avisota\\Recipient\\MutableRecipient::hasDetails()"],["m","Avisota\\Recipient\\MutableRecipient::set()"],["m","Avisota\\Recipient\\MutableRecipient::setDetails()"],["m","Avisota\\Recipient\\MutableRecipient::setEmail()"],["c","Avisota\\Recipient\\MutableRecipientDataException"],["c","Avisota\\Recipient\\RecipientInterface"],["m","Avisota\\Recipient\\RecipientInterface::get()"],["m","Avisota\\Recipient\\RecipientInterface::getDetails()"],["m","Avisota\\Recipient\\RecipientInterface::getEmail()"],["m","Avisota\\Recipient\\RecipientInterface::getKeys()"],["m","Avisota\\Recipient\\RecipientInterface::hasDetails()"],["c","Avisota\\RecipientSource\\CSVFile"],["p","Avisota\\RecipientSource\\CSVFile::$config"],["m","Avisota\\RecipientSource\\CSVFile::__construct()"],["m","Avisota\\RecipientSource\\CSVFile::getRecipientOptions()"],["m","Avisota\\RecipientSource\\CSVFile::getRecipients()"],["c","Avisota\\RecipientSource\\RecipientSourceInterface"],["m","Avisota\\RecipientSource\\RecipientSourceInterface::getRecipientOptions()"],["m","Avisota\\RecipientSource\\RecipientSourceInterface::getRecipients()"],["c","Avisota\\Templating\\MessageTemplateInterface"],["m","Avisota\\Templating\\MessageTemplateInterface::render()"],["c","Avisota\\Transport\\SwiftTransport"],["p","Avisota\\Transport\\SwiftTransport::$mailerImplementation"],["m","Avisota\\Transport\\SwiftTransport::createMailerConfig()"],["m","Avisota\\Transport\\SwiftTransport::finaliseTransport()"],["m","Avisota\\Transport\\SwiftTransport::initialiseTransport()"],["m","Avisota\\Transport\\SwiftTransport::transportEmail()"],["m","Avisota\\Transport\\SwiftTransport::transportNewsletter()"],["c","Avisota\\Transport\\TransportInterface"],["m","Avisota\\Transport\\TransportInterface::flush()"],["m","Avisota\\Transport\\TransportInterface::initialise()"],["m","Avisota\\Transport\\TransportInterface::transport()"]];
