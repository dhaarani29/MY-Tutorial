<?php
/**
 * @SWG\Swagger(
 *     basePath="/web/api",
 *     host="transfer.local",
 *     schemes={"http"},
 *     produces={"application/json"},
 *     consumes={"application/json"},
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Swagger Quizzing",
 *         description="A sample API that uses a quiziing poc as an example to demonstrate features in the swagger-2.0 specification",
 *         termsOfService="http://helloreverb.com/terms/",
 *         @SWG\Contact(name="Wordnik API Team"),
 *         @SWG\License(name="MIT")
 *     ),
 *     @SWG\Definition(
 *         definition="errorModel",
 *         required={"code", "message"},
 *         @SWG\Property(
 *             property="code",
 *             type="integer",
 *             format="int32"
 *         ),
 *         @SWG\Property(
 *             property="message",
 *             type="string"
 *         )
 *     )
 * )
 */
